<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPEKTRA Auth')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-16 w-auto" src="{{ asset('assets/logo-spektra.svg') }}" alt="Logo SPEKTRA" />
            <h1 class="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900">@yield('title')</h1>
            <p class="mt-2 text-center text-sm text-gray-500">@yield('subtitle')</p>
        </div>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
