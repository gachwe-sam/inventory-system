<!doctype html>
<html lang="eng">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Edit supplier</title>
        <head>
            <body>
                <h1> Edit supplier</h1>
                <a href="{{ route('suppliers.index')}}">Back</a>

                @if($errors-> any())
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('suppliers.update',$supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name',$supplier->name) }}">
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" name="emai" value="{{ old('email',$supplier->email)  }}">
                    </div>

                    <div>
                        <label> Item ID </label>
                        <input type="number" name="item_id" value="{{ old('item_id',$supplier->item_id) }}">
                    </div>

                    <div>
                        <label>Description</label>
                        <textarea name="description"> {{ old( 'name',$supplier->description) }}</textarea>
                    </div>

                    <button type="submit">UPDATE SUPPLIER</button>
                </form>
</body>
</html>
