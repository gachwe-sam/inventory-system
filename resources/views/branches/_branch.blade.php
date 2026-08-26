{{-- Recursive partial: renders one branch row, then re-includes itself for each child. --}}
<tr>
    <td style="padding-left: {{ $depth * 24 }}px;">
        {{ $depth > 0 ? '— ' : '' }}
        <a href="{{ route('branches.show', $branch) }}">{{ $branch->name }}</a>
    </td>
    <td>{{ $branch->items_count ?? $branch->stock()->count() }}</td>
    <td>
        <a href="{{ route('branches.create', ['parent_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary">Add Subbranch</a>
        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('branches.destroy', $branch) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this branch and all of its subbranch?')">Delete</button>
        </form>
    </td>
</tr>
@foreach($branch->childrenRecursive as $child)
    @include('branches._branch', ['branch' => $child, 'depth' => $depth + 1])
@endforeach
