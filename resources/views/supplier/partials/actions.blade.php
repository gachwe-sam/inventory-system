<a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
<form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
</form>
