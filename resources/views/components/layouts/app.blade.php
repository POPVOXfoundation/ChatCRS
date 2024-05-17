<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body class="bg-gray-200 h-screen">
        <header class="bg-blue-500 text-white p-4 text-lg font-semibold shadow-lg w-full fixed top-0 left-0 z-10">
            CRSbot (beta)
        </header>
        {{ $slot }}
    </body>
</html>
