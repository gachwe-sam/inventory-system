<a href="{{ route('branches.create', ['parent_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary">Add Subbranch</a>
<a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this branch and all of its subbranches?')">Delete</button>
</form>
