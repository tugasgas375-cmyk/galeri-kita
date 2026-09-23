<?php

namespace App\Http\Controllers;

use App\Models\Moment;
use Illuminate\Http\Request;

class MomentController extends Controller
{
    public function index()
    {
        $moments = Moment::query()
            ->with('photos')
            ->orderByDesc('moment_date')
            ->paginate(config('gallery.home_per_page'));

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
