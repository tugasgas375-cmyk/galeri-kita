@extends('layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-rose-100/70 bg-gradient-to-br from-rose-50 via-white to-amber-50 p-7 sm:p-10">
        <p class="font-serif text-lg italic text-rose-400">selamat datang kembali, yaa</p>
        <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900">Dashboard</h1>
        <p class="mt-2 max-w-lg text-stone-500">Kelola semua momen-momen kalian di sini, sayang.</p>

        <div class="mt-7 grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-lg text-rose-500">&#128248;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ number_format($stats['moments'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Momen</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-500">&#128247;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ number_format($stats['photos'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Foto</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-lg text-rose-500">&#10084;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ number_format($stats['likes'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Total like</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-500">&#128065;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ number_format($stats['views'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Total dilihat</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-3.5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-lg text-violet-500">&#128100;</span>
                <div>
                    <p class="text-2xl font-bold leading-none text-stone-900">{{ number_format($stats['unique_viewers'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-stone-400">Penonton unik</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Widget statistik --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-rose-100/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-400">
                <span class="text-rose-400">&#128202;</span> Kunjungan 6 Bulan Terakhir
            </div>
            <div class="mt-5 flex items-end justify-between gap-2 border-b border-rose-100 pb-1">
                @foreach ($monthlyViews as $month)
                    <div class="flex flex-1 flex-col items-center gap-1.5">
                        <span class="text-[11px] font-semibold text-stone-500">{{ $month['count'] }}</span>
                        <div class="flex h-28 w-full max-w-10 items-end overflow-hidden rounded-t-lg bg-rose-50">
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-rose-400 to-rose-600" style="height: {{ max(2, round(($month['count'] / $monthlyViewsMax) * 100)) }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-stone-400">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-stone-400">
                {{ number_format($stats['views_7_days'], 0, ',', '.') }} kunjungan 7 hari terakhir &bull;
                {{ number_format($stats['views_30_days'], 0, ',', '.') }} dalam 30 hari terakhir
            </p>
        </div>

        <div class="rounded-2xl border border-rose-100/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-400">
                <span class="text-rose-400">&#128293;</span> Momen Paling Dilihat
            </div>
            @if ($topMoments->isEmpty())
                <p class="mt-6 text-sm text-stone-400">Belum ada data kunjungan.</p>
            @else
                <ol class="mt-4 space-y-2">
                    @foreach ($topMoments as $item)
                        <li class="flex items-center gap-3 rounded-xl bg-stone-50 px-4 py-3">
                            <span class="font-serif text-xl font-bold text-rose-300">{{ $loop->iteration }}</span>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.moments.photos', $item) }}" class="block truncate font-medium text-stone-800 transition hover:text-rose-600">
                                    {{ $item->title }}
                                </a>
                                <p class="text-xs text-stone-400">{{ $item->photos_count }} foto</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                &#128065; {{ number_format($item->views, 0, ',', '.') }}
                            </span>
                            <span class="shrink-0 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                &#10084; {{ number_format($item->likes, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ol>
            @endif
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
                        @if ($moment->tags)
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                @foreach ($moment->tags as $tag)
                                    <a href="{{ route('admin.dashboard', ['q' => $tag]) }}" class="rounded-full bg-rose-50 px-2.5 py-0.5 text-[11px] font-medium text-rose-600 transition hover:bg-rose-100">
                                        #{{ $tag }}
                                    </a>
                                @endforeach
                            </div>
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