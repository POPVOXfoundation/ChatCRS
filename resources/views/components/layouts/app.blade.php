<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Page Title' }}</title>
</head>
<body class="bg-gray-200 h-full">
<div class="flex flex-col h-full">
    <header class="shrink-0 bg-pvox-dark">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <svg class="h-8 w-8 text-pvox-orange mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m5 8h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h2l4 4z" />
                </svg>
                <span class="text-2xl font-bold text-white">Chat</span>
                <span class="text-2xl font-bold text-pvox-orange">CRS</span>
            </div>
            <div class="flex items-center gap-x-8">
                <button id="info-show" type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-300">
                    <span class="sr-only">View notifications</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>
    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>
    <footer class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-5 md:flex md:items-center md:justify-between lg:px-8">
            <div class="mt-8 md:order-1 md:mt-0">
                <p class="text-center text-xs leading-5 text-gray-500">&copy; {{ date('Y') }} The POPVOX Foundation</p>
            </div>
        </div>
    </footer>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('info-show').addEventListener('click', function () {
            Livewire.dispatch('info-clicked');
        });
    });
</script>
</body>
</html>
