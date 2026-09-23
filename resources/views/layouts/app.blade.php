<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ config('app.tagline', 'Galeri momen-momen terindah kita berdua.') }}">
        <title>@yield('title', config('app.name')) — {{ config('app.tagline') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:italic,500,600,700|instrument-sans:400,500,600|great-vibes" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-blush text-stone-800 antialiased">
        {{-- Floating hearts background --}}
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="hero-blob left-[-10%] top-[-5%] h-[420px] w-[420px] bg-rose-200/70"></div>
            <div class="hero-blob right-[-8%] top-[15%] h-[380px] w-[380px] bg-amber-100/80"></div>
            <div class="hero-blob bottom-[-12%] left-[20%] h-[460px] w-[460px] bg-rose-100/80"></div>
            <span class="absolute left-[8%] top-[26%] animate-heart-bob text-3xl text-rose-300/60">&#10084;</span>
            <span class="absolute right-[12%] top-[38%] animate-heart-bob text-2xl text-rose-400/50 [animation-delay:0.8s]">&#10084;</span>
            <span class="absolute bottom-[18%] left-[18%] animate-heart-bob text-2xl text-amber-400/50 [animation-delay:1.6s]">&#10084;</span>
            <span class="absolute right-[22%] bottom-[26%] animate-heart-bob text-4xl text-rose-300/50 [animation-delay:2.4s]">&#10084;</span>
        </div>

        <header class="sticky top-0 z-40 border-b border-rose-100/80 bg-white/70 backdrop-blur-xl">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-rose-400 to-rose-600 text-lg text-white shadow-lg shadow-rose-200 transition group-hover:scale-110 group-hover:rotate-6">
                        &#10084;
                    </span>
                    <span class="font-serif text-2xl font-bold tracking-wide text-stone-900">{{ config('app.name') }}</span>
                </a>
                <div class="flex items-center gap-5 text-sm font-medium">
                    <a href="{{ route('home') }}" class="relative text-stone-500 transition hover:text-rose-600 after:absolute after:-bottom-1 after:left-1/2 after:h-0.5 after:w-0 after:-translate-x-1/2 after:rounded-full after:bg-rose-500 after:transition-all hover:after:w-full">Beranda</a>
                    <a href="{{ route('home') }}#momen" class="hidden text-stone-500 transition hover:text-rose-600 after:absolute after:-bottom-1 after:left-1/2 after:h-0.5 after:w-0 after:-translate-x-1/2 after:rounded-full after:bg-rose-500 after:transition-all after:content-[''] hover:after:w-full sm:block">Momen</a>
                    <a href="{{ route('gallery') }}" class="hidden text-stone-500 transition hover:text-rose-600 after:absolute after:-bottom-1 after:left-1/2 after:h-0.5 after:w-0 after:-translate-x-1/2 after:rounded-full after:bg-rose-500 after:transition-all after:content-[''] hover:after:w-full sm:block">Galeri</a>
                    <a href="{{ route('admin.login') }}" class="rounded-full border border-rose-200 bg-white/60 px-5 py-2 text-rose-600 transition hover:border-rose-400 hover:bg-rose-50">
                        Admin
                    </a>
                </div>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="relative mt-24 overflow-hidden">
            <div class="h-px w-full bg-gradient-to-r from-transparent via-rose-300 to-transparent"></div>
            <div class="mx-auto max-w-6xl px-4 py-12 text-center sm:px-6">
                <p class="text-2xl text-rose-400">&#10084;</p>
                <p class="mt-2 font-serif text-xl italic text-stone-600">{{ config('app.name') }}</p>
                <p class="mt-1 text-sm text-stone-400">Dibuat dengan cinta untuk kenangan yang tak lekang waktu</p>
                <div class="mt-6 flex items-center justify-center gap-3 text-xs text-stone-300">
                    <span>&copy; {{ now()->year }}</span>
                    <span>&bull;</span>
                    <span class="font-medium text-stone-400">kita &amp; selamanya</span>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>