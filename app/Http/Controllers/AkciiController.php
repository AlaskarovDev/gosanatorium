<?php

namespace App\Http\Controllers;

use App\Models\Akcii;

class AkciiController extends Controller
{
    public function index($locale = null)
    {
        $akcii = Akcii::visible()->with('service.thumbnail', 'service.translation')->paginate(12);
        $placeholders = $akcii->total() < 4 ? Akcii::withPlaceholders($akcii->getCollection(), 4) : null;

        return view('akcii.index', compact('akcii', 'placeholders'));
    }

    public function show($slug, $extra = null)
    {
        // Supports both /akczii/{slug} and /{locale}/akczii/{slug}
        $slug = $extra ?? $slug;
        $akcia = Akcii::where('slug', $slug)->where('is_active', true)
            ->with('service.thumbnail', 'service.translation')->firstOrFail();

        return view('akcii.show', compact('akcia'));
    }
}
