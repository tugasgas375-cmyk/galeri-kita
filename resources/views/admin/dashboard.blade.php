@extends('layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-rose-100/70 bg-gradient-to-br from-rose-50 via-white to-amber-50 p-7 sm:p-10">
        <p class="font-serif text-lg italic text-rose-400">selamat datang kembali, yaa</p>
        <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900">Dashboard</h1>
        <p class="mt-2 max-w-lg text-stone-500">Kelola semua momen-momen kalian di sini, sayang.</p>

        <div class="mt-7 flex flex-wrap gap-4">
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-lg text-rose-500">&#128248;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ $moments->total() }}</p>
                    <p class="mt-1 text-xs text-stone-400">Momen</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-500">&#128247;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ \App\Models\Photo::count() }}</p>
                    <p class="mt-1 text-xs text-stone-400">Foto</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-500">&#128065;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ \App\Models\Moment::sum('views') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Total orang melihat</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="font-serif text-2xl font-bold text-stone-900">Daftar Momen</h2>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-2">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari momen..."
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100 sm:w-56"
            >
            <button type="submit" class="shrink-0 rounded-xl bg-stone-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-stone-900">
                Cari
            </button>
            @if (request('q'))
                <a href="{{ route('admin.dashboard') }}" class="shrink-0 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-500 transition hover:bg-stone-50">
                    &times;
                </a>
            @endif
        </form>
    </div>

    @if ($moments->isEmpty())
        <div class="mt-8 rounded-[2rem] border border-dashed border-rose-200 bg-white/70 py-16 text-center shadow-sm backdrop-blur">
            <p class="text-6xl">&#128248;</p>
            <h3 class="mt-4 font-serif text-3xl font-bold text-stone-800">
                @if (request('q'))
                    Tidak ditemukan
                @else
                    Belum ada momen
                @endif
            </h3>
            <p class="mx-auto mt-2 max-w-md text-stone-500">
                @if (request('q'))
                    Tidak ada momen yang cocok dengan kata kunci "{{ request('q') }}".
                @else
                    Mulai dengan menambahkan momen pertama kalian berdua.
                @endif
            </p>
            <a href="{{ route('admin.moments.create') }}" class="mt-7 inline-block rounded-full bg-gradient-to-r from-rose-500 to-rose-600 px-8 py-3 text-sm font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5">
                Tambah Momen Pertama
            </a>
        </div>
    @else
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($moments as $moment)
                <div class="card-lift group overflow-hidden rounded-2xl border border-rose-100/60 bg-white shadow-sm">
                    <div class="relative">
                        @if ($moment->coverPhoto())
                            <img
                                src="{{ $moment->coverPhoto()->url }}"
                                alt="{{ $moment->title }}"
                                class="h-48 w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-48 w-full items-center justify-center bg-gradient-to-br from-rose-100 to-amber-100 text-4xl text-rose-300">&#10084;</div>
                        @endif
                        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold text-rose-600 backdrop-blur">
                            {{ $moment->photos_count }} foto
                        </span>
                        <span class="absolute right-3 top-3 rounded-full bg-black/50 px-3 py-1 text-[11px] text-white backdrop-blur">
                            &#128065; {{ number_format($moment->views, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-semibold uppercase tracking-widest text-rose-500">
                            {{ $moment->moment_date->translatedFormat('d F Y') }}
                        </p>
                        <h2 class="mt-1 font-serif text-xl font-bold text-stone-900">{{ $moment->title }}</h2>
                        @if ($moment->description)
                            <p class="mt-1 line-clamp-2 text-sm text-stone-500">{{ $moment->description }}</p>
                        @endif

                        <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                            <a href="{{ route('admin.moments.photos', $moment) }}" class="rounded-lg bg-rose-50 px-3 py-1.5 font-medium text-rose-600 transition hover:bg-rose-100">
                                Kelola Foto
                            </a>
                            <a href="{{ route('admin.moments.edit', $moment) }}" class="rounded-lg bg-stone-100 px-3 py-1.5 font-medium text-stone-600 transition hover:bg-stone-200">
                                Edit
                            </a>
                            <a href="{{ route('moments.show', $moment) }}" target="_blank" class="rounded-lg bg-stone-50 px-3 py-1.5 text-stone-400 transition hover:bg-stone-100" title="Lihat di website">
                                &#128279;
                            </a>
                            <form method="POST" action="{{ route('admin.moments.destroy', $moment) }}"
                                  onsubmit="return confirm('Hapus momen &quot;{{ $moment->title }}&quot; beserta semua fotonya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 font-medium text-red-600 transition hover:bg-red-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $moments->withQueryString()->links('pagination::rose') }}
        </div>
    @endif
@endsection