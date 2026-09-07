<a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>
