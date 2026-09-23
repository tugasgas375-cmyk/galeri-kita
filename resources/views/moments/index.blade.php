@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="mx-auto flex max-w-6xl flex-col items-center px-4 pt-16 text-center sm:px-6 lg:pt-24">
            <span class="animate-fade-up rounded-full border border-rose-200 bg-white/70 px-5 py-1.5 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500 shadow-sm backdrop-blur">
                &hearts; our love story &hearts;
            </span>

            <h1 class="mt-7 animate-fade-up font-serif text-[17vw] font-bold leading-none tracking-tight text-stone-900 sm:text-8xl lg:text-9xl [animation-delay:0.1s]">
                Our <span class="text-gradient-rose italic">Memories</span>
            </h1>

            <p class="mt-6 max-w-xl animate-fade-up text-lg leading-relaxed text-stone-500 [animation-delay:0.2s]">
                <span class="font-serif text-2xl italic text-rose-400">Kumpulan momen kecil</span> yang tercatat begitu indah —
                setiap foto menyimpan cerita, setiap cerita menyimpan kita.
            </p>

            <a href="#momen" class="group mt-10 inline-flex animate-fade-up items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-rose-600 px-9 py-3.5 font-medium text-white shadow-xl shadow-rose-200 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-rose-300 [animation-delay:0.3s]">
                Lihat Momen Kita
                <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&darr;</span>
            </a>

            {{-- Days together --}}
            <div class="mt-12 animate-fade-up rounded-full border border-rose-200/70 bg-white/70 px-7 py-3 shadow-lg shadow-rose-100/60 backdrop-blur [animation-delay:0.4s]">
                <p class="font-serif text-lg italic text-stone-600">
                    <span class="text-rose-500">&#10084;</span>
                    Sudah <span class="font-bold not-italic text-rose-600" id="days-together">0</span> hari kita bersama —
                    dan terus bertambah setiap detiknya.
                </p>
            </div>

            {{-- Polaroid strip --}}
            @if ($moments->count() >= 1)
                <div class="mt-16 flex w-full flex-wrap items-end justify-center gap-6 sm:gap-10">
                    @foreach ($moments->take(3)->loadMissing('photos') as $moment)
                        <a href="{{ route('moments.show', $moment) }}"
                           class="polaroid group block w-40 animate-float-slow rounded-lg sm:w-52"
                           style="animation-delay: {{ $loop->index * 1.6 }}s">
                            @if ($moment->coverPhoto())
                                <img src="{{ $moment->coverPhoto()->url }}" alt="{{ $moment->title }}"
                                     class="h-44 w-full rounded-sm object-cover sm:h-56">
                            @else
                                <div class="flex h-44 w-full items-center justify-center rounded-sm bg-rose-100 text-4xl text-rose-300 sm:h-56">
                                    &#10084;
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mt-16 flex gap-6 sm:gap-10">
                    <div class="polaroid w-40 animate-float-slow rounded-lg sm:w-52">
                        <div class="flex h-44 w-full items-center justify-center rounded-sm bg-gradient-to-br from-rose-200 to-amber-100 text-5xl text-rose-400/70 sm:h-56">&#128247;</div>
                    </div>
                    <div class="hidden polaroid w-40 animate-float-slow rounded-lg sm:block sm:w-52" style="animation-delay:1.4s">
                        <div class="flex h-44 w-full items-center justify-center rounded-sm bg-gradient-to-br from-rose-200 to-amber-100 text-5xl text-rose-400/70 sm:h-56">&#10084;</div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- MOMENTS --}}
    <section id="momen" class="mx-auto max-w-6xl scroll-mt-24 px-4 pt-28 sm:px-6">
        <div class="mb-14 text-center">
            <p class="font-serif text-lg italic text-rose-400">kenangan yang kita simpan</p>
            <h2 class="mt-1 font-serif text-4xl font-bold text-stone-900 sm:text-5xl">Momen <span class="text-gradient-rose">Kita</span></h2>
            <div class="mx-auto mt-5 flex items-center justify-center gap-2">
                <span class="h-px w-16 bg-rose-300"></span>
                <span class="text-rose-400">&#10084;</span>
                <span class="h-px w-16 bg-rose-300"></span>
            </div>
        </div>

        @forelse ($moments as $moment)
            <article class="reveal mb-16">
                <div class="card-lift overflow-hidden rounded-[2rem] border border-rose-100/70 bg-white shadow-sm">
                    <div class="flex flex-col lg:flex-row">
                        {{-- Cover --}}
                        <a href="{{ route('moments.show', $moment) }}" class="group relative block overflow-hidden lg:w-[42%]">
                            @if ($moment->coverPhoto())
                                <img
                                    src="{{ $moment->coverPhoto()->url }}"
                                    alt="{{ $moment->title }}"
                                    class="h-72 w-full object-cover transition duration-700 group-hover:scale-110 lg:h-full lg:min-h-[380px]"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-72 w-full items-center justify-center bg-gradient-to-br from-rose-100 to-amber-100 text-6xl text-rose-300 lg:h-full">
                                    &#10084;
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition duration-500 group-hover:opacity-100"></div>
                            <div class="absolute inset-x-0 bottom-0 flex translate-y-4 items-center justify-center pb-6 opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                                <span class="rounded-full bg-white/90 px-5 py-2 text-sm font-medium text-rose-600 backdrop-blur">
                                    Buka galeri &rarr;
                                </span>
                            </div>
                            <span class="absolute right-3 top-3 rounded-full bg-black/50 px-3 py-1.5 text-xs font-medium text-white backdrop-blur">
                                &#128065; {{ number_format($moment->views, 0, ',', '.') }}
                            </span>
                        </a>

                        {{-- Info --}}
                        <div class="flex flex-1 flex-col justify-center p-7 sm:p-10">
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-rose-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-rose-500">
                                    {{ $moment->moment_date->translatedFormat('d F Y') }}
                                </span>
                                <span class="flex items-center gap-1 text-xs text-stone-400">
                                    <span class="text-rose-400">&#10084;</span>
                                    {{ $moment->photos_count ?? $moment->photos->count() }} foto
                                </span>
                            </div>

                            <h3 class="mt-4 font-serif text-3xl font-bold leading-tight text-stone-900 sm:text-4xl">
                                <a href="{{ route('moments.show', $moment) }}" class="transition hover:text-rose-600">
                                    {{ $moment->title }}
                                </a>
                            </h3>

                            @if ($moment->description)
                                <p class="mt-4 max-w-xl leading-relaxed text-stone-500">{{ Str::limit($moment->description, 240) }}</p>
                            @endif

                            <a href="{{ route('moments.show', $moment) }}" class="mt-7 inline-flex w-fit items-center gap-2 rounded-full border border-rose-200 px-6 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-600 hover:text-white">
                                Kenang momen ini
                                <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="reveal mx-auto max-w-xl rounded-[2rem] border border-dashed border-rose-200 bg-white/70 p-12 text-center shadow-sm backdrop-blur">
                <p class="text-6xl">&#128248;</p>
                <h3 class="mt-5 font-serif text-3xl font-bold text-stone-800">Belum ada momen</h3>
                <p class="mx-auto mt-3 max-w-md leading-relaxed text-stone-500">
                    Momen-momen pertama kalian akan muncul di sini. Biarkan cerita ini mulai ditulis.
                </p>
                <a href="{{ route('admin.login') }}" class="mt-7 inline-block rounded-full bg-gradient-to-r from-rose-500 to-rose-600 px-8 py-3 text-sm font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5">
                    Tambah Momen Pertama
                </a>
            </div>
        @endforelse

        @if ($moments->hasPages())
            <div class="reveal mt-4 flex justify-center">
                {{ $moments->links('pagination::rose') }}
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealEls = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window)) {
                revealEls.forEach((el) => el.classList.add('reveal-visible'));
                return;
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            revealEls.forEach((el) => observer.observe(el));

            const counter = document.getElementById('days-together');
            if (counter) {
                const start = Math.floor(new Date('{{ config('gallery.first_date') }}T00:00:00').getTime() / 1000);
                const now = Math.floor(Date.now() / 1000);
                counter.textContent = Math.max(0, Math.floor((now - start) / 86400)).toLocaleString('id-ID');
            }
        });
    </script>
@endpush