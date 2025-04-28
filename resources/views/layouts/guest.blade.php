<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="transform hover:scale-105 transition-all duration-300 ease-in-out">
                <a href="/" class="flex items-center space-x-2 group">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300" />
                    <span class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors duration-300">BlogApp</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg
                transform transition-all duration-500 ease-in-out hover:shadow-lg
                animate-[fadeIn_0.5s_ease-in-out]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
