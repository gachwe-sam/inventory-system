<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Branchstock;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->filteredItems($request)
            ->paginate(6)
            ->withQueryString();

        $categoryOptions = $this->categoryOptions();

        return view('items.index', compact('items', 'categoryOptions'));
    }
        public function show(Item $item)
    {
        $item->load('category.parent');

        $user = auth()->user();

        $stockRows = $user->hasRole('admin')
            ? $item->stock()->with('branch')->get()
            : $item->stock()->with('branch')->where('branch_id', $user->branch_id)->get();

        return view('items.show', compact('item', 'stockRows'));
    }



    
    public function exportExcel(Request $request, string $format)
    {
        $items = $this->filteredItems($request)->get();

        return Excel::download(new ItemsExport($items), "items.{$format}");
    }

   
     public function exportPdf(Request $request)
    {
        $items = $this->filteredItems($request)->get();

        $pdf = Pdf::loadView('items.export-pdf', compact('items'));

        return $pdf->download('items.pdf');
    }

       private function filteredItems(Request $request)
    {
        return Item::with('category.parent', 'stock')
            ->search($request->input('search'))
            ->inCategory($request->integer('category_id') ?: null)
            ->lowStock($request->boolean('low_stock'));
    }

  
    public function import(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|file|mimes:xlsx,csv',
        ]);

        $import = new ItemsImport();
        Excel::import($import, $request->file('spreadsheet'));

        session(['last_import_ids' => $import->importedIds]);

        $message = count($import->importedIds) . ' item(s) imported.';

        if (count($import->skipped) > 0) {
            $message .= ' ' . count($import->skipped) . ' row(s) skipped.';
        }

        return redirect()->route('items.index')
            ->with('success', $message)
            ->with('import_skipped', $import->skipped);
    }

   
    public function undoImport(Request $request)
    {
        $ids = session('last_import_ids', []);

        Item::whereIn('id', $ids)->delete();

        session()->forget('last_import_ids');

        return redirect()->route('items.index')->with('success', count($ids) . ' imported item(s) removed.');
    }

    public function create()
    {
        $categoryOptions = $this->categoryOptions();

        return view('items.create', compact('categoryOptions'));
    }

        public function store(Request $request)
    {
        $validated = $this->validateItem($request);

        $item = Item::create($validated);

        if (auth()->user()->branch_id) {
            Branchstock::create([
                'branch_id' => auth()->user()->branch_id,
                'item_id' => $item->id,
                'quantity' => 0,
                'reorder_level' => 0,
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item created.');
    }


    public function edit(Item $item)
    {
        $categoryOptions = $this->categoryOptions();

        return view('items.edit', compact('item', 'categoryOptions'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $this->validateItem($request);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted.');
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);

                    if ($category && ! $category->isLeaf()) {
                        $fail("\"{$category->name}\" has its own subcategories — please choose the lowest-level subcategory instead.");
                    }
                },
            ],
            'expiry_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric',
        ]);
    }

    
    private function categoryOptions()
    {
        $flattened = collect();

        $walk = function ($categories, int $depth) use (&$walk, &$flattened) {
            foreach ($categories as $category) {
                $flattened->push([
                    'id' => $category->id,
                    'label' => str_repeat('— ', $depth) . $category->name,
                   
                    'is_leaf' => $category->childrenRecursive->isEmpty(),
                ]);
                $walk($category->childrenRecursive, $depth + 1);
            }
        };

        $walk(Category::tree(), 0);

        return $flattened;
    }

    private function validateSupplier(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'item_id' => [
                'required',
                'exists:items,id',
                function ($attribute, $value, $fail) {
                    $item = Item::find($value);

                    if ($item && ! $item->is_active) {
                        $fail("The item \"{$item->name}\" exists but is inactive.");
                    }
                },
            ],
        ]);
    }
}
