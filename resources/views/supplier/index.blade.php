<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content=["width=device-width, initial-scale=1.0">
        <title>suppliers</title>
</head>
<body>
    <h1>Suppliers </h1>
    <a href="{{ route('suppliers.create')}}"> ADD SUPPLIER </a>
    @if (session('success'))
        <p>{{session('success')}} </p>
    @endif

    <table border-"1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Item<th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->id}}</td>
            <td>{{ $supplier->name}}</td>
            <td>{{ $supplier->email}}</td>
            <td>{{ $supplier->item?->name ?? 'N/A'}}</td>
            <td>
                <a href="{{ route('suppliers.show', $supplier)}}">view</a>
                <a href="{{ route('suppliers.edit'$supplier)}}"> Edit</a>

                <form action="{{route('suppliers.destroy', $supplier)  }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="5">No suppliers yet.</td>
            </tr>
            @endforelse
    </tbody>
</table>
{{ $suppliers->links()}}
</body>
</html>



        

