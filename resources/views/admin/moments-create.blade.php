@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-stone-500 transition hover:text-rose-600">
        <span aria-hidden="true">&larr;</span> Kembali ke dashboard
    </a>

    <p class="font-serif text-lg italic text-rose-400">satu cerita baru</p>
    <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900">Tambah Momen Baru</h1>
    <p class="mt-2 text-stone-500">Ceritakan sebuah momen dan unggah foto-fotonya.</p>

    <form method="POST" action="{{ route('admin.moments.store') }}" enctype="multipart/form-data" class="mt-10 max-w-2xl space-y-6">
        @csrf

        <div class="rounded-2xl border border-rose-100/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-400">
                <span class="text-rose-400">&#128221;</span> Detail Momen
            </div>
            <div class="space-y-5">
                <div>
                    <label for="title" class="mb-1.5 block text-sm font-medium text-stone-700">Judul Momen *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Mis: Kencan Pertama di Pantai"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >
                    @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="moment_date" class="mb-1.5 block text-sm font-medium text-stone-700">Tanggal Momen *</label>
                    <input
                        type="date"
                        id="moment_date"
                        name="moment_date"
                        value="{{ old('moment_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >
                    @error('moment_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-stone-700">Deskripsi / Cerita</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Ceritakan momen tersebut dengan kata-katamu sendiri..."
                        class="w-full resize-none rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-rose-100/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-400">
                <span class="text-rose-400">&#128247;</span> Foto-Foto
            </div>

            <label
                for="photos"
                id="dropzone"
                class="mt-3 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-rose-200 bg-rose-50/40 px-6 py-12 text-center transition hover:border-rose-400 hover:bg-rose-50"
            >
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-rose-100 to-amber-100 text-3xl">&#128247;</span>
                <span class="mt-4 text-sm font-medium text-stone-700">Klik untuk memilih foto</span>
                <span class="mt-1 text-xs text-stone-400">atau tarik &amp; letakkan di sini</span>
                <span class="mt-3 rounded-full bg-white/80 px-3 py-1 text-[11px] text-rose-500">JPG, PNG, GIF, WEBP &bull; maks 10 MB</span>
            </label>
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple class="hidden" onchange="previewPhotos(event)">
            @error('photos') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror

            <div id="preview-count" class="mt-5 hidden">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-4 py-1.5 text-xs font-semibold text-rose-600">
                    <span>&#10084;</span> <span id="preview-count-text">0</span> foto dipilih
                </span>
            </div>

            <div id="preview" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3"></div>
        </div>

        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 py-3.5 font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:shadow-xl sm:w-auto sm:px-12">
            Simpan Momen &hearts;
        </button>
    </form>

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
                cell.className = 'card-lift overflow-hidden rounded-xl bg-white shadow-sm';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-28 w-full object-cover';
                        cell.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const img = document.createElement('div');
                    img.className = 'flex h-28 w-full items-center justify-center bg-stone-100 text-3xl text-stone-400';
                    img.textContent = '&#128196;';
                    cell.appendChild(img);
                }

                const label = document.createElement('label');
                label.className = 'block px-2.5 py-2';
                label.innerHTML = '<span class="text-[11px] font-medium text-stone-500">Caption foto ' + (index + 1) + '</span>';
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'captions[]';
                input.placeholder = 'Mis: ketawa bareng';
                input.className = 'mt-1 w-full rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100';
                label.appendChild(input);
                cell.appendChild(label);

                preview.appendChild(cell);
            });
        }
    </script>
@endsection