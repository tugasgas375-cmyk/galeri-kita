@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-stone-500 transition hover:text-rose-600">
        <span aria-hidden="true">&larr;</span> Kembali ke dashboard
    </a>

    <p class="font-serif text-lg italic text-rose-400">mari poles ceritanya</p>
    <h1 class="mt-1 font-serif text-4xl font-bold text-stone-900">Edit Momen</h1>
    <p class="mt-2 text-stone-500">Perbarui detail momen "{{ $moment->title }}".</p>

    <form method="POST" action="{{ route('admin.moments.update', $moment) }}" class="mt-10 max-w-2xl space-y-6">
        @csrf
        @method('PUT')

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
                        value="{{ old('title', $moment->title) }}"
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
                        value="{{ old('moment_date', $moment->moment_date->format('Y-m-d')) }}"
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
                        rows="5"
                        placeholder="Ceritakan momen tersebut dengan kata-katamu sendiri..."
                        class="w-full resize-none rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >{{ old('description', $moment->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tags_string" class="mb-1.5 block text-sm font-medium text-stone-700">Tag / Kategori</label>
                    <input
                        type="text"
                        id="tags_string"
                        name="tags_string"
                        value="{{ old('tags_string', $moment->tags ? implode(', ', $moment->tags) : '') }}"
                        placeholder="Mis: date, travel, anniversary"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >
                    <p class="mt-1.5 text-xs text-stone-400">Pisahkan dengan koma, maksimal 10 tag. Tag dipakai untuk filter di galeri.</p>
                    @error('tags_string') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('admin.moments.photos', $moment) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-6 py-3 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                &#128247; Kelola {{ $moment->photos_count }} foto
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 px-10 py-3 font-medium text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:shadow-xl sm:w-auto">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection