<a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this purchase?')">Delete</button>
</form>
