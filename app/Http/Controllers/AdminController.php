<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use App\Models\Photo;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $moments = Moment::withCount('photos')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%'.$request->string('q').'%')
                        ->orWhere('description', 'like', '%'.$request->string('q').'%');
                });
            })
            ->orderByDesc('moment_date')
            ->paginate(12);

        return view('admin.dashboard', compact('moments'));
    }

    public function create()
    {
        return view('admin.moments-create');
    }

    public function store(Request $request)
    {
        $data = $this->validateMoment($request);

        $moment = Moment::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'moment_date' => $data['moment_date'],
        ]);

        $this->savePhotos($moment, $data['photos'] ?? [], $data['captions'] ?? []);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Momen "'.$moment->title.'" berhasil ditambahkan!');
    }

    public function edit(Moment $moment)
    {
        return view('admin.moments-edit', compact('moment'));
    }

    public function update(Request $request, Moment $moment)
    {
        $data = $this->validateMoment($request, true);

        $moment->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'moment_date' => $data['moment_date'],
        ]);

        return redirect()->route('admin.moments.photos', $moment)
            ->with('success', 'Momen "'.$moment->title.'" berhasil diperbarui.');
    }

    public function photos(Moment $moment)
    {
        $moment->load('photos');

        return view('admin.moment-photos', compact('moment'));
    }

    public function storePhotos(Request $request, Moment $moment)
    {
        $data = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
        ]);

        $this->savePhotos($moment, $data['photos'], $data['captions'] ?? []);

        return back()->with('success', count($data['photos']).' foto berhasil ditambahkan.');
    }

    public function updateCaption(Request $request, Photo $photo)
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $photo->update(['caption' => $data['caption'] ?? null]);

        return back()->with('success', 'Caption foto diperbarui.');
    }

    public function destroy(Moment $moment)
    {
        foreach ($moment->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $moment->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Momen berhasil dihapus.');
    }

    public function destroyPhoto(Photo $photo)
    {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function setCover(Photo $photo)
    {
        $photo->moment->photos()->update(['is_cover' => false]);
        $photo->update(['is_cover' => true]);

        return back()->with('success', 'Foto sampul berhasil diubah.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'exists:photos,id'],
        ]);

        foreach (array_values($data['order']) as $index => $photoId) {
            Photo::where('id', $photoId)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Urutan foto berhasil disimpan.');
    }

    public function downloadZIP(Moment $moment)
    {
        $moment->load('photos');

        if ($moment->photos->isEmpty()) {
            return back()->withErrors('Tidak ada foto untuk diunduh.');
        }

        $zip = new ZipArchive;
        $tmp = tempnam(sys_get_temp_dir(), 'momen').'.zip';

        if ($zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors('Gagal membuat arsip zip.');
        }

        foreach ($moment->photos as $index => $photo) {
            $file = Storage::disk('public')->path($photo->path);
            $ext = pathinfo($photo->path, PATHINFO_EXTENSION) ?: 'jpg';
            $name = 'foto-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $zip->addFile($file, $name.'.'.$ext);
        }

        $zip->close();

        return response()->download($tmp, 'album-'.Str::slug($moment->title).'-'.$moment->id.'.zip')
            ->deleteFileAfterSend(true);
    }

    protected function validateMoment(Request $request, bool $allowEmptyPhotos = false)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'moment_date' => ['required', 'date'],
        ];

        if (! $allowEmptyPhotos) {
            $rules['photos'] = ['required', 'array', 'min:1'];
            $rules['photos.*'] = ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'];
            $rules['captions'] = ['nullable', 'array'];
            $rules['captions.*'] = ['nullable', 'string', 'max:255'];
        }

        return $request->validate($rules);
    }

    protected function savePhotos(Moment $moment, array $photos, array $captions)
    {
        $optimizer = new ImageOptimizer;
        $beforeCount = (int) $moment->photos()->count();
        $nextSort = (int) $moment->photos()->max('sort_order') + 1;
        if ($beforeCount === 0) {
            $nextSort = 0;
        }

        foreach ($photos as $index => $file) {
            $tmp = $file->getRealPath();

            if ($tmp && is_file($tmp)) {
                $optimizer->optimize($tmp);
            }

            $path = $file->store('photos/'.$moment->id, 'public');

            Photo::create([
                'moment_id' => $moment->id,
                'path' => $path,
                'caption' => $captions[$index] ?? null,
                'sort_order' => $nextSort + $index,
                'is_cover' => $beforeCount === 0 && $index === 0,
            ]);
        }
    }
}
