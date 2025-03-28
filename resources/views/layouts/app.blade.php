<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @if(session('success'))
            <div id="snackbar" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-300">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="snackbar" class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-300">
                {{ session('error') }}
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var snackbar = document.getElementById('snackbar');
                if (snackbar) {
                    snackbar.classList.remove('opacity-0');
                    setTimeout(function(){
                        snackbar.classList.add('opacity-0');
                    }, 3000);
                }
            });
        </script>
    </body>
</html>
