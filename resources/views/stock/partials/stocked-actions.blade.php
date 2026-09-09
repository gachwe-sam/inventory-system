@can('receive', $row)
    <a href="{{ route('branch-stock.receive.form', $row) }}" class="btn btn-sm btn-success">Receive</a>
@endcan
@can('issue', $row)
    <a href="{{ route('branch-stock.issue.form', $row) }}" class="btn btn-sm btn-warning"><i class="bi bi-box-arrow-up"></i> Issue</a>
@endcan
@can('transfer', $row)
    <a href="{{ route('branch-stock.transfer.form', $row) }}" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right-left"></i> Transfer</a>
@endcan
@can('view', $row)
    <a href="{{ route('branch-stock.history', $row) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history"></i> History</a>
@endcan
@can('update', $row)
    <a href="{{ route('branch-stock.edit', $row) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Reorder Level</a>
@endcan
