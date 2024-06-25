<!DOCTYPE html>
<html>
<head>
    <title>Laravel Article App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            @guest
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{ route('login') }}">Login</a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
            @else
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('users.show', Auth::user()->id) }}">--}}
{{--                        <span class="navbar-text mr-3">Hello, {{ Auth::user()->name }}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{ route('logout') }}"--}}
{{--                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                        Logout--}}
{{--                    </a>--}}
{{--                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                        @csrf--}}
{{--                    </form>--}}
{{--                </li>--}}
            @endguest
        </ul>
    </div>
</nav>
<div class="container">
    @yield('content')
</div>
</body>
</html>
