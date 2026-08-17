<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
     <p><h2>Please log in or register.</h2></p>
    @auth
        <p>Welcome, {{ auth()->user()->name }}!</p>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @else
             
        <div style="border:3px solid brown; margin-bottom: 20px;">
            <h2>REGISTER</h2>
            <form action='/register' method="POST">
                @csrf
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Register</button>
            </form>
        </div>

        <div style="border:3px solid blue;">
            <h2>LOGIN</h2>
            <form action='/login' method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            @if ($errors->any())
                <div style="color: red;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    @endauth

    @if (session('success'))
        <div style="color: green; margin-top: 20px;">
            {{ session('success') }}
        </div>
    @endif
</body>
</html>