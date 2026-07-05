<?php

namespace App\Http\Controllers;

use App\Models\ProdukMarketplace;

class LandingController extends Controller
{
    public function index()
    {
        $produk = ProdukMarketplace::with('penjual')
            ->where('aktif', true)
            ->whereNull('deleted_at')
            ->latest()
            ->take(6)
            ->get();

        return view('landing.landing', compact('produk'));
    }
}