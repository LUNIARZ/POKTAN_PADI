<?php

namespace App\Http\Controllers;

use App\Models\KontenAplikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KontenAplikasiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('kategori');
        $query = KontenAplikasi::where('status', 'terbit');
        if (in_array($category, ['edukasi', 'hama_penyakit'], true)) {
            $query->where('kategori', $category);
        }

        return response()->json($query->latest('diterbitkan_pada')->get()->map(fn ($item) => [
            'id' => $item->id,
            'category' => $item->kategori === 'hama_penyakit' ? 'Hama & Penyakit' : 'Edukasi',
            'title' => $item->judul,
            'type' => ucfirst($item->jenis_konten),
            'description' => $item->deskripsi,
            'image' => $item->gambar,
            'link' => $item->tautan,
        ]));
    }
}
