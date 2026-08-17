<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category.parent')->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categoryOptions = $this->categoryOptions();

        return view('items.create', compact('categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateItem($request);

        Item::create($validated);

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
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric',
            'expiry_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric',
            'reorder_level' => 'nullable|numeric',
        ]);
    }

    /**
     * Every category, flattened out with an indentation depth so the <select>
     * can show the tree (e.g. "Beverages" then "-- Cold Beverage" beneath it).
     */
    private function categoryOptions()
    {
        $flattened = collect();

        $walk = function ($categories, int $depth) use (&$walk, &$flattened) {
            foreach ($categories as $category) {
                $flattened->push([
                    'id' => $category->id,
                    'label' => str_repeat('— ', $depth) . $category->name,
                ]);
                $walk($category->childrenRecursive, $depth + 1);
            }
        };

        $walk(Category::tree(), 0);

        return $flattened;
    }
}
