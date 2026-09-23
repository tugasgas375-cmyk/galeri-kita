@extends('layouts.app')

@section('title', 'Galeri')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
        <div class="mb-12 text-center">
            <p class="font-serif text-lg italic text-rose-400">setiap jepretan menyimpan cerita</p>
            <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900 sm:text-5xl">
                Semua <span class="text-gradient-rose">Foto</span>
            </h1>
            <div class="mx-auto mt-5 flex items-center justify-center gap-2">
                <span class="h-px w-16 bg-rose-300"></span>
                <span class="text-rose-400">&#10084;</span>
                <span class="h-px w-16 bg-rose-300"></span>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('gallery') }}" class="reveal mx-auto mt-8 flex w-full max-w-md flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <select name="momen" onchange="this.form.submit()"
                        class="w-full cursor-pointer rounded-full border border-rose-200/80 bg-white/80 px-5 py-3 text-sm text-stone-700 shadow-sm outline-none backdrop-blur transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100 sm:w-auto">
                    <option value="">Semua momen</option>
                    @foreach ($moments as $moment)
                        <option value="{{ $moment->id }}" @selected(request('momen') == $moment->id)>{{ $moment->title }}</option>
                    @endforeach
                </select>
                @if (request('momen') || request('tag'))
                    <a href="{{ route('gallery') }}" class="shrink-0 rounded-full border border-stone-200 bg-white px-4 py-3 text-sm text-stone-500 transition hover:bg-stone-50">
                        &times; Hapus filter
                    </a>
                @endif
            </form>

            @if (count($tags))
                <div class="mx-auto mt-4 flex max-w-3xl flex-wrap items-center justify-center gap-2">
                    <a href="{{ route('gallery') }}"
                       class="rounded-full px-4 py-2 text-sm transition {{ request('tag') ? 'border border-stone-200 bg-white text-stone-500 hover:bg-stone-50' : 'bg-gradient-to-r from-rose-500 to-rose-600 font-medium text-white shadow-md shadow-rose-200' }}">
                        Semua
                    </a>
                    @foreach ($tags as $tag)
                        <a href="{{ route('gallery', array_filter(['tag' => $tag, 'momen' => request('momen')])) }}"
                           class="rounded-full border px-4 py-2 text-sm transition {{ request('tag') === $tag ? 'bg-gradient-to-r from-rose-500 to-rose-600 font-medium text-white shadow-md shadow-rose-200' : 'border-rose-200 bg-white/80 text-rose-600 hover:bg-rose-50' }}">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($photos->count())
            <div class="columns-1 gap-5 sm:columns-2 lg:columns-3 xl:columns-4 [&>*]:mb-5">
                @foreach ($photos as $photo)
                    <figure class="reveal break-inside-avoid overflow-hidden rounded-2xl bg-white shadow-md transition-shadow duration-300 hover:shadow-2xl hover:shadow-rose-200">
                        <a href="{{ $photo->url }}" class="glightbox group block" data-caption="{{ $photo->caption ?: $photo->moment->title }}" data-title="{{ $photo->moment->title }}">
                            <img
                                src="{{ $photo->url }}"
                                alt="{{ $photo->caption ?: $photo->moment->title }}"
                                class="w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                        </a>
                        <div class="px-5 py-3.5">
                            <a href="{{ route('moments.show', $photo->moment) }}" class="text-xs font-semibold uppercase tracking-widest text-rose-500 transition hover:text-rose-700">
                                {{ $photo->moment->title }}
                            </a>
                            @if ($photo->caption)
                                <p class="mt-1 text-sm leading-relaxed text-stone-500">{{ $photo->caption }}</p>
                            @endif
                        </div>
                    </figure>
                @endforeach
            </div>
        @else
            <div class="reveal mx-auto max-w-xl rounded-[2rem] border border-dashed border-rose-200 bg-white/70 p-12 text-center shadow-sm backdrop-blur">
                <p class="text-6xl">&#128248;</p>
                <h3 class="mt-5 font-serif text-3xl font-bold text-stone-800">Belum ada foto</h3>
                <p class="mx-auto mt-3 max-w-md leading-relaxed text-stone-500">
                    Foto-foto dari semua momen akan berkumpul di sini. Mulai dari menambahkan momen pertamamu.
                </p>
                <a href="{{ route('home') }}" class="mt-7 inline-block rounded-full bg-gradient-to-r from-rose-500 to-rose-600 px-8 py-3 text-sm font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5">
                    Kembali ke beranda
                </a>
            </div>
        @endif

        @if ($photos->hasPages())
            <div class="reveal mt-4 flex justify-center">
                {{ $photos->links('pagination::rose') }}
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    @include('partials.lightbox')
@endpush