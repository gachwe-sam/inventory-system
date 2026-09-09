<a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
<form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this purchase?')"><i class="bi bi-trash"></i> Delete</button>
</form>
