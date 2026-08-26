<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory System')</title>
    {{-- Bootstrap via CDN — matches the btn/table/form-control classes
         your existing views already use --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Inventory System</a>
            <div class="navbar-nav">
                @auth
                    <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                    <a class="nav-link" href="{{ route('items.index') }}">Items</a>
                    @role('admin')
                        <a class="nav-link" href="{{ route('branches.index') }}">Branches</a>
                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                    @endrole
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    {{-- @yield pulls in whatever the child view defines inside
         @section('content') ... @endsection --}}
    @yield('content')
</body>
</html>