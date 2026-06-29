<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PengaturanController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = DB::table('pengaturan_aplikasi')->where('id', 1)->first();
        $methods = MetodePembayaran::all();

        return response()->json([
            'marketplace' => ucfirst($settings->status_marketplace ?? 'aktif'),
            'maintenance' => ($settings->maintenance_aktif ?? false) ? 'Aktif' : 'Nonaktif',
            'maintenanceMessage' => $settings->pesan_maintenance ?? 'Aplikasi sedang dalam perawatan.',
            'buyerPaymentDisabledMethods' => $methods->where('konteks', 'marketplace_pembeli')
                ->where('aktif', false)->pluck('nama_tampilan')->values(),
            'farmerPaymentDisabledMethods' => $methods->where('konteks', 'pupuk_petani')
                ->where('aktif', false)->pluck('nama_tampilan')->values(),
        ]);
    }
}
