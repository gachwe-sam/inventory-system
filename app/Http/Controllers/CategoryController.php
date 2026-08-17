<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Top-level categories with their whole subtree eager-loaded, for a nested list.
        $categories = Category::tree();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentOptions = $this->parentOptions();

        return view('categories.create', compact('parentOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    /**
     * Show a category plus every item filed under it or any of its subcategories,
     * at any depth (e.g. "Beverages" also pulls items filed under "Cold Beverage").
     */
    public function show(Category $category)
    {
        $items = $category->itemsIncludingDescendants()->with('category')->get();

        return view('categories.show', compact('category', 'items'));
    }

    public function edit(Category $category)
    {
        $parentOptions = $this->parentOptions($category);

        return view('categories.edit', compact('category', 'parentOptions'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        // SoftDeletes trait turns this into a soft delete; Category::booted() cascades
        // it to every descendant category so the whole branch disappears together.
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ];

        $validated = $request->validate($rules);

        if ($category && $validated['parent_id']) {
            $this->guardAgainstCycle($category, (int) $validated['parent_id']);
        }

        return $validated;
    }

    /**
     * A category can't be parented to itself or to one of its own descendants —
     * that would create a loop the recursive CTE would walk forever.
     */
    private function guardAgainstCycle(Category $category, int $newParentId): void
    {
        if ($newParentId === $category->id) {
            abort(422, 'A category cannot be its own parent.');
        }

        if ($category->descendantIds()->contains($newParentId)) {
            abort(422, 'A category cannot be moved under one of its own subcategories.');
        }
    }

    /**
     * Categories eligible to be picked as a parent in the create/edit form.
     * When editing, excludes the category itself and all of its descendants.
     */
    private function parentOptions(?Category $category = null)
    {
        $query = Category::with('parent')->orderBy('name');

        if ($category) {
            $excluded = $category->descendantAndSelfIds();
            $query->whereNotIn('id', $excluded);
        }

        return $query->get();
    }
}
