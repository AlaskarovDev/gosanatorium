<?php

namespace App\Http\Controllers;

use App\Models\NaftalanProduct;

class NaftalanProductsController extends Controller
{
    public function index($locale = 'ru')
    {
        try {
            $products = NaftalanProduct::active()->get();
        } catch (\Throwable $e) {
            $products = collect();
        }

        return view('pages.naftalan-products', compact('products'));
    }
}
