<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:italic,500,600,700|instrument-sans:400,500,600|great-vibes" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-blush text-stone-800 antialiased">
        <header class="sticky top-0 z-30 border-b border-rose-100/80 bg-white/75 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-rose-400 to-rose-600 text-base text-white shadow-md shadow-rose-200">&#10084;</span>
                    <span class="font-serif text-xl font-bold text-stone-900">{{ config('app.name') }}</span>
                    <span class="hidden rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-500 sm:block">Admin</span>
                </a>
                <div class="flex items-center gap-3 text-sm">
                    <a href="{{ route('home') }}" class="hidden items-center gap-1.5 text-stone-500 transition hover:text-rose-600 sm:flex" target="_blank">
                        <span>Lihat Website</span><span aria-hidden="true">&#8599;</span>
                    </a>
                    <a href="{{ route('admin.moments.create') }}" class="rounded-full bg-gradient-to-r from-rose-500 to-rose-600 px-5 py-2 font-medium text-white shadow-md shadow-rose-200 transition hover:-translate-y-0.5">
                        + Momen Baru
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-stone-200 bg-white/60 px-4 py-2 text-stone-500 transition hover:border-red-200 hover:text-red-600">
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
            @if (session('success'))
                <div class="mb-8 flex items-start gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    <span class="mt-0.5">&#10003;</span>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>