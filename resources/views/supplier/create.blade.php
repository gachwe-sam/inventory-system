<doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Supplier</title>
    </head>
    <body>
        <h1>Create Supplier</h1>

        <a href="{{ route('suppliers.index') }}">Back to Suppliers</a>

        @if(errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        @endif
        
        <form action="{{route('suppliers.store')}}" method="POST">
            @csrf

            <div>
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name')}}">
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email)}}">
            </div>

            <div>
                <label>Item ID </label>
                <input type="number" name="item_id" value="{{ old('item_id')}}">
            </div>

            <div>
                <label>Description </label>
                <textarea name="description">{{ old('description')}}</textarea>
            </div>

            <button type="submit">SAVE SUPPLIER</BUTTON>
        </form>
</body>
</html>


