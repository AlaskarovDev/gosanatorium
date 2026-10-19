<?php

namespace App\Http\Controllers;

use App\Models\Akcii;

class AkciiController extends Controller
{
    public function index($locale = null)
    {
        $akcii = Akcii::active()->paginate(12);

        return view('akcii.index', compact('akcii'));
    }
}
