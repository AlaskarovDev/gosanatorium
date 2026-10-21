<?php

namespace App\Http\Controllers;

use App\Models\NaftalanProduct;

class NaftalanProductsController extends Controller
{
    public function index($locale = 'ru')
    {
        $products = NaftalanProduct::active()->get();

        return view('pages.naftalan-products', compact('products'));
    }
}
