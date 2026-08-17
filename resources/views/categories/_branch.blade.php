{{-- Recursive partial: renders one category row, then re-includes itself for each child. --}}
<tr>
    <td style="padding-left: {{ $depth * 24 }}px;">
        {{ $depth > 0 ? '— ' : '' }}
        <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
    </td>
    <td>{{ $category->items_count ?? $category->items()->count() }}</td>
    <td>
        <a href="{{ route('categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary">Add Subcategory</a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category and all of its subcategories?')">Delete</button>
        </form>
    </td>
</tr>
@foreach($category->childrenRecursive as $child)
    @include('categories._branch', ['category' => $child, 'depth' => $depth + 1])
@endforeach
