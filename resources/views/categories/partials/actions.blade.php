<a href="{{ route('categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> Add Subcategory</a>
<a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
<form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category and all of its subcategories?')"><i class="bi bi-trash"></i> Delete</button>
</form>
