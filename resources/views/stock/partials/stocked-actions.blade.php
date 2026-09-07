@can('receive', $row)
    <a href="{{ route('branch-stock.receive.form', $row) }}" class="btn btn-sm btn-success">Receive</a>
@endcan
@can('issue', $row)
    <a href="{{ route('branch-stock.issue.form', $row) }}" class="btn btn-sm btn-warning">Issue</a>
@endcan
@can('transfer', $row)
    <a href="{{ route('branch-stock.transfer.form', $row) }}" class="btn btn-sm btn-primary">Transfer</a>
@endcan
@can('view', $row)
    <a href="{{ route('branch-stock.history', $row) }}" class="btn btn-sm btn-outline-secondary">History</a>
@endcan
@can('update', $row)
    <a href="{{ route('branch-stock.edit', $row) }}" class="btn btn-sm btn-outline-warning">Reorder Level</a>
@endcan
