
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'WARUNG POJOK') - Sistem Kasir</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/warung-pojok.css') }}?v=4">
</head>

<body class="font-sans antialiased">
    <div class="wp-app min-h-screen">
        @include('layouts.navigation')

        <div class="wp-main">
            @isset($header)
                <header class="wp-page-header">
                    {{ $header }}
                </header>
            @endisset

            <main class="wp-content">
                {{ $slot }}
            </main>

            <footer class="wp-footer">
                <span>© {{ date('Y') }} WARUNG POJOK</span>
                <span>Sistem Informasi Kasir</span>
            </footer>
        </div>
    </div>
</body>
</html>
