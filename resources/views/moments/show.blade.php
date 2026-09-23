@extends('layouts.app')

@section('title', $moment->title)

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
        <a href="{{ route('home') }}" class="group mb-8 inline-flex items-center gap-2 text-sm font-medium text-stone-500 transition hover:text-rose-600">
            <span class="transition-transform group-hover:-translate-x-1" aria-hidden="true">&larr;</span> Kembali ke beranda
        </a>

        {{-- Header --}}
        <div class="mb-12 max-w-3xl">
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-rose-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-rose-500">
                    {{ $moment->moment_date->translatedFormat('d F Y') }}
                </span>
                <span class="flex items-center gap-1 text-xs text-stone-400">
                    <span class="text-rose-400">&#10084;</span>
                    {{ $moment->photos->count() }} foto
                </span>
                <span class="flex items-center gap-1 text-xs text-stone-400">
                    <span class="text-rose-400">&#128065;</span>
                    {{ number_format($moment->views, 0, ',', '.') }} orang melihat
                </span>
            </div>
            <h1 class="mt-4 font-serif text-4xl font-bold leading-tight text-stone-900 sm:text-5xl lg:text-6xl">
                {{ $moment->title }}
            </h1>
            <div class="mt-6 flex items-center gap-2">
                <span class="h-px w-16 bg-rose-300"></span>
                <span class="text-rose-400">&#10084;</span>
                <span class="h-px w-16 bg-rose-300"></span>
            </div>
            @if ($moment->description)
                <p class="mt-6 whitespace-pre-line font-serif text-xl italic leading-relaxed text-stone-600">{{ $moment->description }}</p>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('moments.download', $moment) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-6 py-2.5 text-sm font-medium text-amber-700 transition hover:bg-amber-100">
                    &#11015; Unduh album (ZIP)
                </a>
                <a href="https://wa.me/?text={{ urlencode($moment->title.' — '.route('moments.show', $moment)) }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full border border-emerald-300 bg-emerald-50 px-6 py-2.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">
                    <span class="flex h-4 w-4 items-center justify-center text-sm leading-none" aria-hidden="true">&#128172;</span>
                    Bagikan ke WhatsApp
                </a>
                <button type="button" id="copy-link"
                        class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-6 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                    &#128279; Salin link
                </button>
            </div>
        </div>

        {{-- Gallery --}}
        <div class="columns-1 gap-5 sm:columns-2 lg:columns-3 [&>*]:mb-5">
            @foreach ($moment->photos as $photo)
                <figure class="reveal break-inside-avoid overflow-hidden rounded-2xl bg-white shadow-md transition-shadow duration-300 hover:shadow-2xl hover:shadow-rose-200">
                    <a href="{{ $photo->url }}" class="glightbox block" data-caption="{{ $photo->caption ?: $moment->title }}" data-title="{{ $moment->title }}">
                        <img
                            src="{{ $photo->url }}"
                            alt="{{ $photo->caption ?: $moment->title }}"
                            class="w-full object-cover transition duration-700 hover:scale-105"
                            loading="lazy"
                        >
                    </a>
                    @if ($photo->caption)
                        <figcaption class="px-5 py-3.5">
                            <p class="text-sm leading-relaxed text-stone-500">{{ $photo->caption }}</p>
                        </figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const links = Array.from(document.querySelectorAll('.glightbox'));
            if (!links.length) return;

            let current = 0;

            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-50 flex flex-col items-center justify-center bg-black/95 p-4 opacity-0 pointer-events-none transition-opacity duration-300';
            overlay.innerHTML = `
                <button type="button" class="close absolute right-5 top-5 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-xl text-white transition hover:bg-white/25" aria-label="Tutup">&times;</button>
                <button type="button" class="prev absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25 sm:left-8" aria-label="Sebelumnya">&lsaquo;</button>
                <button type="button" class="next absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25 sm:right-8" aria-label="Berikutnya">&rsaquo;</button>
                <img class="max-h-[78vh] max-w-[92vw] rounded-xl shadow-2xl" alt="">
                <p class="caption mt-5 min-h-6 max-w-lg text-center font-serif text-lg italic text-rose-100"></p>
                <p class="counter mt-2 text-xs tracking-widest text-white/50"></p>
            `;
            document.body.appendChild(overlay);

            const img = overlay.querySelector('img');
            const caption = overlay.querySelector('.caption');
            const counter = overlay.querySelector('.counter');
            const closeBtn = overlay.querySelector('.close');
            const prevBtn = overlay.querySelector('.prev');
            const nextBtn = overlay.querySelector('.next');

            function show(index) {
                current = (index + links.length) % links.length;
                const link = links[current];
                img.src = link.getAttribute('href');
                caption.textContent = link.dataset.caption || '';
                counter.textContent = (current + 1) + ' / ' + links.length;
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            }

            links.forEach((link, i) => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    show(i);
                });
            });

            closeBtn.addEventListener('click', close);
            prevBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current - 1); });
            nextBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current + 1); });

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) close();
            });

            document.addEventListener('keydown', function (e) {
                if (overlay.classList.contains('pointer-events-none')) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowLeft') show(current - 1);
                if (e.key === 'ArrowRight') show(current + 1);
            });

            function close() {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                img.src = '';
            }
        });

        const copyBtn = document.getElementById('copy-link');
        if (copyBtn) {
            copyBtn.addEventListener('click', async function () {
                const target = copyBtn.dataset.url || window.location.href;
                try {
                    await navigator.clipboard.writeText(target);
                    copyBtn.textContent = '\u2713 Link disalin';
                } catch (e) {
                    const fallback = document.createElement('textarea');
                    fallback.value = target;
                    document.body.appendChild(fallback);
                    fallback.select();
                    document.execCommand('copy');
                    fallback.remove();
                    copyBtn.textContent = '\u2713 Link disalin';
                }
                copyBtn.classList.add('bg-rose-600', 'text-white');
                setTimeout(() => {
                    copyBtn.innerHTML = '&#128279; Salin link';
                    copyBtn.classList.remove('bg-rose-600', 'text-white');
                }, 2000);
            });
        }
    </script>
@endpush