<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RYU-DASHBOARD</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @yield('css')
</head>
<body>
    <div id="app">
        <div class="flex flex-row justify-between items-center bg-yellow-500 px-7 md:px-32 py-4">
            <a class="text-xl font-semibold text-white" href="{{ url('/') }}">
                RyuJin
            </a>
            <div class="flex flex-row gap-x-3 text-white">
                    {{-- @guest --}}
                            <a href="/login">Login</a>
                        {{-- @if (Route::has('register')) --}}
                            <a href="/register">Register</a>
                        {{-- @endif --}}
                    {{-- @else
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    @endguest --}}
            </div>
        </div>
        <main class="px-7 md:px-32 py-4">
            @yield('content')
        </main>
    </div>
    @yield('js')
</body>
</html>