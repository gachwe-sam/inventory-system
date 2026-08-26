<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>    INVENTORY MANAGEMENT SYSTEM </h2>
    <h2>Categories</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent Category</th>
                <th>Items Count</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent?->name ?? '' }}</td>
                <td>{{ $category->items()->count() }}</td>
            </tr>
            @empty
            <tr><td colspan="4">No categories.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
