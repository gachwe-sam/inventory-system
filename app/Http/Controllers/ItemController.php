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
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);

                    // Items must live at the bottom of the tree: if the chosen
                    // category still has subcategories of its own, it isn't the
                    // most specific place this item could be filed.
                    if ($category && ! $category->isLeaf()) {
                        $fail("\"{$category->name}\" has its own subcategories — please choose the lowest-level subcategory instead.");
                    }
                },
            ],
            'quantity' => 'required|numeric',
            'expiry_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric',
            'reorder_level' => 'nullable|numeric',
        ]);
    }

    /**
     * Every category, flattened out with an indentation depth so the <select>
     * can show the tree (e.g. "Beverages" then "-- Cold Beverage" beneath it).
     * is_leaf flags categories that still have subcategories, so the view can
     * gray those out — items may only be filed under a leaf category.
     */
    private function categoryOptions()
    {
        $flattened = collect();

        $walk = function ($categories, int $depth) use (&$walk, &$flattened) {
            foreach ($categories as $category) {
                $flattened->push([
                    'id' => $category->id,
                    'label' => str_repeat('— ', $depth) . $category->name,
                    // childrenRecursive is already eager-loaded by Category::tree(),
                    // so this is a plain collection check, not an extra query.
                    'is_leaf' => $category->childrenRecursive->isEmpty(),
                ]);
                $walk($category->childrenRecursive, $depth + 1);
            }
        };

        $walk(Category::tree(), 0);

        return $flattened;
    }
}
