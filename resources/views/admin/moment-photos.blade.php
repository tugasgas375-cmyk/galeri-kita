@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-stone-500 transition hover:text-rose-600">
        <span aria-hidden="true">&larr;</span> Kembali ke dashboard
    </a>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="font-serif text-lg italic text-rose-400">{{ $moment->moment_date->translatedFormat('d F Y') }}</p>
            <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900">{{ $moment->title }}</h1>
            <p class="mt-2 text-stone-500">{{ $moment->photos->count() }} foto &bull; dibuka {{ $moment->views }} kali</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('moments.download', $moment) }}"
               class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-5 py-2.5 text-sm font-medium text-amber-700 transition hover:bg-amber-100">
                &#11015; Unduh ZIP
            </a>
            <a href="{{ route('admin.moments.edit', $moment) }}"
               class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-5 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                &#9998; Edit momen
            </a>
        </div>
    </div>

    {{-- Tambah foto --}}
    <div class="mt-10 rounded-2xl border border-rose-100/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-400">
            <span class="text-rose-400">&#10133;</span> Tambah Foto ke Momen Ini
        </div>

        <form method="POST" action="{{ route('admin.moments.photos.store', $moment) }}" enctype="multipart/form-data">
            @csrf

            <label
                for="photos"
                id="dropzone"
                class="mt-3 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-rose-200 bg-rose-50/40 px-6 py-10 text-center transition hover:border-rose-400 hover:bg-rose-50"
            >
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-rose-100 to-amber-100 text-2xl">&#128247;</span>
                <span class="mt-3 text-sm font-medium text-stone-700">Klik untuk menambah foto</span>
                <span class="mt-1 text-xs text-stone-400">atau tarik &amp; letakkan — JPG, PNG, GIF, WEBP (maks 10 MB)</span>
            </label>
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple class="hidden" onchange="previewPhotos(event)">
            @error('photos') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror

            <div id="preview-count" class="mt-5 hidden">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-4 py-1.5 text-xs font-semibold text-rose-600">
                    <span>&#10084;</span> <span id="preview-count-text">0</span> foto baru dipilih
                </span>
            </div>

            <div id="preview" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"></div>

            <button type="submit" class="mt-5 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 px-8 py-2.5 text-sm font-medium text-white shadow-md shadow-rose-200 transition hover:-translate-y-0.5">
                Simpan Foto Baru
            </button>
        </form>
    </div>

    {{-- Daftar foto --}}
    <div class="mt-10">
        <h2 class="font-serif text-2xl font-bold text-stone-900">Semua Foto</h2>
        <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($moment->photos as $photo)
                <div class="overflow-hidden rounded-2xl border border-rose-100/60 bg-white shadow-sm">
                    <a href="{{ $photo->url }}" target="_blank" class="group relative block">
                        <img src="{{ $photo->url }}" alt="{{ $photo->caption ?: $moment->title }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <span class="absolute right-2 top-2 rounded-full bg-black/60 px-2.5 py-0.5 text-[11px] text-white backdrop-blur">#{{ $loop->iteration }}</span>
                    </a>

                    <form method="POST" action="{{ route('admin.photos.update', $photo) }}" class="p-4">
                        @csrf
                        @method('PATCH')
                        <label class="block text-[11px] font-medium text-stone-400">Caption</label>
                        <input
                            type="text"
                            name="caption"
                            value="{{ $photo->caption }}"
                            placeholder="Tulis keterangan foto..."
                            class="mt-1 w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                        >
                        <button type="submit" class="mt-3 w-full rounded-lg bg-rose-50 px-3 py-2 text-xs font-medium text-rose-600 transition hover:bg-rose-100">
                            Simpan Caption
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}"
                          onsubmit="return confirm('Hapus foto ini?')"
                          class="mx-4 mb-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100">
                            Hapus Foto
                        </button>
                    </form>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-rose-200 bg-white/60 py-12 text-center sm:col-span-2 lg:col-span-3">
                    <p class="text-4xl">&#128248;</p>
                    <p class="mt-3 text-stone-500">Belum ada foto dalam momen ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('photos');
        const dropzone = document.getElementById('dropzone');
        const preview = document.getElementById('preview');
        const previewCount = document.getElementById('preview-count');
        const previewCountText = document.getElementById('preview-count-text');

        ['dragenter', 'dragover'].forEach((name) => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                dropzone.classList.add('border-rose-500', 'bg-rose-100');
            });
        });
        ['dragleave', 'drop'].forEach((name) => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-rose-500', 'bg-rose-100');
            });
        });
        dropzone.addEventListener('drop', (e) => {
            fileInput.files = e.dataTransfer.files;
            previewPhotos({ target: fileInput });
        });

        function previewPhotos(event) {
            const files = Array.from(event.target.files);
            preview.innerHTML = '';
            previewCountText.textContent = files.length;
            previewCount.classList.toggle('hidden', files.length === 0);

            files.forEach((file, index) => {
                const cell = document.createElement('div');
                cell.className = 'overflow-hidden rounded-xl bg-white shadow-sm';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-28 w-full object-cover';
                        cell.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }

                const label = document.createElement('label');
                label.className = 'block px-2.5 py-2';
                label.innerHTML = '<span class="text-[11px] font-medium text-stone-500">Caption foto ' + (index + 1) + '</span>';
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'captions[]';
                input.placeholder = 'Mis: ketawa bareng';
                input.className = 'mt-1 w-full rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-xs outline-none transition focus:border-rose-400';
                label.appendChild(input);
                cell.appendChild(label);

                preview.appendChild(cell);
            });
        }
    </script>
@endsection