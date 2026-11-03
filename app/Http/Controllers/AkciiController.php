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
}
