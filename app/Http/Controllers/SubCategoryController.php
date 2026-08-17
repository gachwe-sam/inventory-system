<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::withCount('items')->get();
        return view('subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        return view('subcategories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        SubCategory::create($request->only('name'));

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created.');
    }

    public function edit(SubCategory $subCategory)
    {
        return view('subcategories.edit', compact('subCategory'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subCategory->update($request->only('name'));

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted.');
    }
}
