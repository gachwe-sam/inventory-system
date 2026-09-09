<a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
<form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')"><i class="bi bi-trash"></i></button>
</form>
