<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use App\Models\MomentLike;
use App\Models\MomentView;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class MomentController extends Controller
{
    public function index(Request $request)
    {
        $moments = Moment::with('photos')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%'.$request->string('q').'%')
                        ->orWhere('description', 'like', '%'.$request->string('q').'%');
                });
            })
            ->filterTag($request->string('tag'))
            ->orderByDesc('moment_date')
            ->paginate(config('gallery.home_per_page'))
            ->withQueryString();

        $tags = Moment::allTags();

        $likedMomentIds = $this->likedMomentIds($request, $moments->pluck('id')->all());

        return view('moments.index', compact('moments', 'tags', 'likedMomentIds'));
    }

    public function gallery(Request $request)
    {
        $photos = Photo::with('moment')
            ->whereHas('moment')
            ->when($request->filled('momen'), function ($query) use ($request) {
                $query->where('moment_id', $request->integer('momen'));
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->whereHas('moment', fn ($q) => $q->filterTag($request->string('tag')));
            })
            ->orderByDesc('id')
            ->paginate(48)
            ->withQueryString();

        $moments = Moment::orderByDesc('moment_date')->get();
        $tags = Moment::allTags();

        return view('moments.gallery', compact('photos', 'moments', 'tags'));
    }

    public function show(Request $request, Moment $moment)
    {
        $moment->load('photos');

        $deviceId = $request->cookie('dk_device_id');

        if (! $deviceId) {
            $deviceId = (string) Str::uuid();
            Cookie::queue('dk_device_id', $deviceId, 60 * 24 * 365 * 5);
        }

        $alreadyViewed = MomentView::where('moment_id', $moment->id)
            ->where('device_id', $deviceId)
            ->exists();

        if (! $alreadyViewed) {
            MomentView::create([
                'moment_id' => $moment->id,
                'device_id' => $deviceId,
                'created_at' => now(),
            ]);
            $moment->increment('views');
        }

        $liked = in_array($moment->id, $this->likedMomentIds($request, [$moment->id]), true);

        return view('moments.show', compact('moment', 'liked'));
    }

    public function toggleLike(Request $request, Moment $moment)
    {
        $deviceId = $request->cookie('dk_device_id');

        if (! $deviceId) {
            $deviceId = (string) Str::uuid();
            Cookie::queue('dk_device_id', $deviceId, 60 * 24 * 365 * 5);
        }

        $existing = MomentLike::where('moment_id', $moment->id)
            ->where('device_id', $deviceId)
            ->first();

        if ($existing) {
            $existing->delete();
            $moment->decrement('likes');
            $liked = false;
        } else {
            MomentLike::create([
                'moment_id' => $moment->id,
                'device_id' => $deviceId,
                'created_at' => now(),
            ]);
            $moment->increment('likes');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes' => (int) $moment->fresh()->likes,
        ]);
    }

    protected function likedMomentIds(Request $request, array $momentIds): array
    {
        $deviceId = $request->cookie('dk_device_id');

        if (! $deviceId || empty($momentIds)) {
            return [];
        }

        return MomentLike::whereIn('moment_id', $momentIds)
            ->where('device_id', $deviceId)
            ->pluck('moment_id')
            ->all();
    }
}
