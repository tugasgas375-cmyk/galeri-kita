<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Admin — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:italic,500,600,700|instrument-sans:400,500,600|great-vibes" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen items-center justify-center bg-blush px-4 py-10 antialiased">
        <div class="hero-blob left-[-10%] top-[-5%] h-[380px] w-[380px] bg-rose-200/70" aria-hidden="true"></div>
        <div class="hero-blob right-[-8%] bottom-[-10%] h-[380px] w-[380px] bg-amber-100/80" aria-hidden="true"></div>

        <div class="relative w-full max-w-md">
            <div class="mb-8 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-rose-400 to-rose-600 text-2xl text-white shadow-lg shadow-rose-200">
                    &#10084;
                </span>
                <a href="{{ route('home') }}" class="mt-4 block font-serif text-3xl font-bold text-stone-900">{{ config('app.name') }}</a>
                <p class="mt-1 font-serif text-lg italic text-rose-400">ruang pribadi kita</p>
                <h1 class="mt-5 text-sm font-medium uppercase tracking-[0.2em] text-stone-500">Masuk ke Panel Admin</h1>
            </div>

            <div class="rounded-[1.75rem] border border-rose-100/70 bg-white/80 p-8 shadow-2xl shadow-rose-100 backdrop-blur sm:p-9">
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-2.5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                        <span class="mt-0.5">&#9888;</span>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-stone-600">Username</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-stone-400">&#128100;</span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                placeholder="admin"
                                class="w-full rounded-xl border border-stone-200 bg-white py-2.5 pl-11 pr-4 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-stone-600">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-stone-400">&#128274;</span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                class="w-full rounded-xl border border-stone-200 bg-white py-2.5 pl-11 pr-4 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                            >
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 py-3 font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:shadow-xl">
                        Masuk
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-stone-400">
                <a href="{{ route('home') }}" class="transition hover:text-rose-600">&larr; Kembali ke galeri</a>
            </p>
        </div>
    </body>
</html>