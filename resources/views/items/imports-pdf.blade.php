<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Items</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Items</h2>
    <h2>    INVENTORY MANAGEMENT SYSTEM </h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Total Stock</th>
                <th>Expiry Date</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    @if($item->category)
                        {{ $item->category->parent ? $item->category->parent->name . ' > ' : '' }}{{ $item->category->name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $item->totalQuantity() }}</td>
                <td>{{ $item->expiry_date?->format('Y-m-d') }}</td>
                <td>{{ $item->unit_price }}</td>
            </tr>
            @empty
            <tr><td colspan="7">No items.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
