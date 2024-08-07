<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Anatomia-Patologica'))</title>

        

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <!-- JS -->
        <script src="{{ asset('js/scripts.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Sidebar -->
            <!-- Layout Sidenav and Content -->
            <div id="layoutSidenav" class="d-flex">
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Main Content -->
                <div id="layoutSidenav_content" class="flex-grow-1 p-4">
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
