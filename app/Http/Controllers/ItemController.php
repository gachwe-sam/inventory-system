<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'subcategory'])->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();

        return view('items.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'quantity' => 'required|numeric',
            'expiry_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric',
            'reorder_level' => 'nullable|numeric',
        ]);

        Item::create($request->only(
            'name',
            'description',
            'category_id',
            'subcategory_id',
            'quantity',
            'expiry_date',
            'unit_price',
            'reorder_level'
        ));

        return redirect()->route('items.index')->with('success', 'Item created.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();

        return view('items.edit', compact('item', 'categories', 'subCategories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'quantity' => 'required|numeric',
            'expiry_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric',
            'reorder_level' => 'nullable|numeric',
        ]);

        $item->update($request->only(
            'name',
            'description',
            'category_id',
            'subcategory_id',
            'quantity',
            'expiry_date',
            'unit_price',
            'reorder_level'
        ));

        return redirect()->route('items.index')->with('success', 'Item updated.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted.');
    }
}
