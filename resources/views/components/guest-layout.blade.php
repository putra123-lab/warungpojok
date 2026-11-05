
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WARUNG POJOK') }}</title>

    {{-- Font tetap Figtree --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen flex flex-col items-center justify-center bg-[#fff7ed] px-4 py-10">

        {{-- Branding WARUNG POJOK --}}
        <div class="mb-7 text-center">
            <a href="/" class="inline-flex flex-col items-center no-underline">

                <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-orange-500 shadow-lg shadow-orange-200">
                    <span class="text-4xl font-extrabold text-white">WP</span>
                </div>

                <h1 class="mt-4 text-3xl font-extrabold tracking-wide text-neutral-900">
                    WARUNG <span class="text-orange-500">POJOK</span>
                </h1>

                <p class="mt-1 text-sm font-medium text-neutral-500">
                    Sistem Kasir dan Manajemen Warung
                </p>

            </a>
        </div>

        {{-- Kotak Login --}}
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-orange-100 bg-white px-7 py-8 shadow-xl shadow-orange-100/60 sm:px-9">

            <div class="mb-6">
                <h2 class="text-xl font-bold text-neutral-900">
                    Selamat Datang!
                </h2>

                <p class="mt-1 text-sm text-neutral-500">
                    Silakan masuk ke akun WARUNG POJOK.
                </p>
            </div>

            {{ $slot }}

        </div>

        {{-- Footer --}}
        <div class="mt-7 text-center text-xs text-neutral-400">
            &copy; {{ date('Y') }} WARUNG POJOK. All rights reserved.
        </div>

    </div>

</body>
</html>
