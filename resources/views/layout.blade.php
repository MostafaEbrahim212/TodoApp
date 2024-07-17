<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-indigo-900 font-body-font text-white transition scroll-smooth">
    <div id="app" class="container">
        <nav class="w-full h-14 rounded-b-lg flex items-center justify-between">
            <a class="text-2xl font-bold hover:cursor-pointer flex items-center justify-center sm:justify-between space-x-1 "
                href="{{ route('home') }}">
                <span class="hidden sm:block">Todo</span>
                <i class="fa-solid fa-table-list text-indigo-300 text-3xl sm:text-2xl"></i>
                <span class="text-indigo-300 hidden sm:block">App</span>
            </a>
            @guest
                <div class="space-x-3 flex items-center">
                    <a class="text-lg font-semibold hover:cursor-pointer hover:text-indigo-300"
                        href="{{ route('login') }}">Login</a>
                    <a class="text-lg font-semibold hover:cursor-pointer hover:text-indigo-300"
                        href="{{ route('register') }}">Register</a>
                </div>
            @endguest
            @auth
                <div class="space-x-3 flex items-center">
                    <span class="text-lg font-semibold">Welcome <span
                            class="text-indigo-300">{{ auth()->user()->name }}</span></span>
                    <a class="text-lg font-semibold hover:cursor-pointer bg-indigo-300 p-2 rounded-lg hover:bg-indigo-500"
                        href="{{ route('profile') }}">Profile</a>
                    <a class="text-lg font-semibold hover:cursor-pointer bg-indigo-300 p-2 rounded-lg hover:bg-indigo-500"
                        href="{{ route('logout') }}">Logout</a>
                </div>
            @endauth
        </nav>
    </div>
    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('scripts')
    <script></script>
</body>

</html>
