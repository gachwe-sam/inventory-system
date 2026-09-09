{{-- Recursive partial: renders one category row, then re-includes itself for each child. --}}
<tr>
    <td style="padding-left: {{ $depth * 24 }}px;">
        {{ $depth > 0 ? '— ' : '' }}
        <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
    </td>
    <td>{{ $category->items_count ?? $category->items()->count() }}</td>
    <td>
        <a href="{{ route('categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i> Add Subcategory</a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category and all of its subcategories?')"><i class="bi bi-trash"></i> Delete</button>
        </form>
    </td>
</tr>
@foreach($category->childrenRecursive as $child)
    @include('categories._branch', ['category' => $child, 'depth' => $depth + 1])
@endforeach
