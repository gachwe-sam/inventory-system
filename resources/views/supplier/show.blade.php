<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Details</title>
</head>
<body>
    <h1>{{ $supplier->name }}</h1>

    <a href="{{ route('suppliers.index') }}"> Back to suppliers</a>

    <p><strong>Email:</strong>{{ $supplier->email ?? 'N/A' }}</p>
    <p><strong>Item ID:</strong>{{ $supplier->item_id ?? 'N/A' }}</p>
    <p><strong>Description:</strong>{{ $supplier->description ?? 'No description' }}</p>

    <a href="{{ route('suppliers.edit', $supplier) }}"> Edit </a>

    <form action="{{ route('suppliers.destroy',$supplier) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
</body>
</html>
          
