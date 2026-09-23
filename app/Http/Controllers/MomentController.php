<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use Illuminate\Http\Request;

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
            ->orderByDesc('moment_date')
            ->paginate(config('gallery.home_per_page'))
            ->withQueryString();

        return view('moments.index', compact('moments'));
    }

    public function show(Request $request, Moment $moment)
    {
        $moment->load('photos');

        $cameFromOwnSite = $request->headers->get('referer')
            && str_contains($request->headers->get('referer'), parse_url(url('/'), PHP_URL_HOST));

        if (! $cameFromOwnSite) {
            $moment->increment('views');
        }

        return view('moments.show', compact('moment'));
    }
}
