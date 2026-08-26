<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    $orderedIds = Category::treeOrderedIds();

    $matching = Category::with('parent')
        ->search($request->input('search'))
        ->whereIn('id', $orderedIds)
        ->get()
        ->sortBy(fn ($category) => $orderedIds->search($category->id))
        ->values();

    $perPage = 14;
    $page = $request->integer('page', 1);

    $categories = new LengthAwarePaginator(
        $matching->forPage($page, $perPage)->values(),
        $matching->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    $categoryOptions = $this->parentOptions();

    return view('categories.index', compact('categories', 'categoryOptions'));
}

    public function create()
    {
        $parentOptions = $this->parentOptions();

        return view('categories.create', compact('parentOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        
        $trashed = Category::onlyTrashed()
            ->where('parent_id', $validated['parent_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($trashed) {
            $trashed->restore();
        } else {
            Category::create($validated);
        }

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    /**
     * Drills into a category: if it still has subcategories, show those
     * (clicking further narrows the tree — "Fertilizer" -> "CAN" -> "25 KG
     * BAG") instead of jumping straight to an items table. Items only
     * appear once you reach a leaf category, matching the existing rule
     * that items may only ever be filed on a leaf (see Item::validateItem).
     */
    public function show(Category $category)
    {
        $subcategories = $category->children()->withCount('items')->orderBy('name')->get();

        if ($subcategories->isNotEmpty()) {
            return view('categories.show', [
                'category' => $category,
                'subcategories' => $subcategories,
                'items' => null,
            ]);
        }

        $items = $category->items()->with('category')->get();

        return view('categories.show', [
            'category' => $category,
            'subcategories' => null,
            'items' => $items,
        ]);
    }

    public function edit(Category $category)
    {
        $parentOptions = $this->parentOptions($category);

        return view('categories.edit', compact('category', 'parentOptions'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category);
        
        Category::onlyTrashed()
            ->where('parent_id', $validated['parent_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $category->id)
            ->forceDelete();

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
               
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('parent_id', $request->input('parent_id')))
                    ->whereNull('deleted_at')
                    ->ignore($category?->id),
            ],
            'parent_id' => 'nullable|exists:categories,id',
        ];

        $validated = $request->validate($rules);

        if ($category && $validated['parent_id']) {
            $this->guardAgainstCycle($category, (int) $validated['parent_id']);
        }

        return $validated;
    }

    
    private function guardAgainstCycle(Category $category, int $newParentId): void
    {
        if ($newParentId === $category->id) {
            abort(422, 'A category cannot be its own parent.');
        }

        if ($category->descendantIds()->contains($newParentId)) {
            abort(422, 'A category cannot be moved under one of its own subcategories.');
        }
    }

   
    private function parentOptions(?Category $category = null)
    {
        $query = Category::with('parent')->orderBy('name');

        if ($category) {
            $excluded = $category->descendantAndSelfIds();
            $query->whereNotIn('id', $excluded);
        }

        return $query->get();
    }

    public function exportExcel(Request $request, string $format)
    {
        $categories = $this->filteredCategories($request)->get();

        return Excel::download(new CategoriesExport($categories), "categories.{$format}");
    }

    public function exportPdf(Request $request)
    {
        $categories = $this->filteredCategories($request)->get();

        $pdf = Pdf::loadView('categories.export-pdf', compact('categories'));

        return $pdf->download('categories.pdf');
    }

    private function filteredCategories(Request $request)
    {
        return Category::with('parent')
            ->orderBy('name')
            ->search($request->input('search'));
    }

    /**
     * Same session-based, single-undo-slot approach as ItemController::import()
     * — deliberately small, no persisted batch history yet.
     */
    public function import(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|file|mimes:xlsx,csv',
        ]);

        $import = app()->make(\App\Imports\CategoriesImport::class);
        Excel::import($import, $request->file('spreadsheet'));

        session(['last_category_import_ids' => $import->importedIds]);

        $count = count($import->importedIds);
        $message = $count . ' categor' . ($count === 1 ? 'y' : 'ies') . ' created.';

        if (count($import->skipped) > 0) {
            $message .= ' ' . count($import->skipped) . ' row(s) skipped.';
        }

        return redirect()->route('categories.index')
            ->with('success', $message)
            ->with('import_skipped', $import->skipped);
    }

    /**
     * Soft-deletes exactly the categories the most recent import created
     * (parents included, not just leaves) — safe to click more than once,
     * and doesn't touch anything that already existed before the import.
     */
    public function undoImport(Request $request)
    {
        $ids = session('last_category_import_ids', []);

        Category::whereIn('id', $ids)->delete();

        session()->forget('last_category_import_ids');

        $count = count($ids);

        return redirect()->route('categories.index')
            ->with('success', $count . ' imported categor' . ($count === 1 ? 'y' : 'ies') . ' removed.');
    }
}
