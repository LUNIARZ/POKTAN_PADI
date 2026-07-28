<?php

namespace App\Http\Controllers;

use App\Models\BatasPupukPetani;
use App\Models\DetailPesananPupuk;
use App\Models\MetodePembayaran;
use App\Models\NotifikasiAplikasi;
use App\Models\PesananPupuk;
use App\Models\ProdukPupuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PupukController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limits = BatasPupukPetani::where('id_petani', $request->user()->id)
            ->where('aktif', true)
            ->get()
            ->keyBy('id_produk_pupuk');
        $methods = MetodePembayaran::where('konteks', 'pupuk_petani')->get();

        return response()->json([
            'products' => ProdukPupuk::where('aktif', true)->get()->map(function ($item) use ($limits) {
                $limit = $limits->get($item->id);

                return [
                    'id' => $item->id,
                    'nama' => $item->nama_produk,
                    'deskripsi' => $item->deskripsi,
                    'harga' => (float) $item->harga,
                    'satuan' => $item->ukuran_kemasan ? '/ '.$item->ukuran_kemasan : '/ karung',
                    'stok' => (int) $item->jumlah_stok,
                    'gambar' => $item->gambar_produk,
                    'dibatasi' => $limit !== null,
                    'batas' => (int) ($limit?->maksimal_jumlah ?? 0),
                ];
            }),
            'paymentMethods' => $methods->mapWithKeys(fn ($item) => [$item->nama_tampilan => $item->aktif]),
            'orders' => $this->orders($request->user()->id),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'transfer', 'qris'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:produk_pupuk,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $methodActive = MetodePembayaran::where('konteks', 'pupuk_petani')
                ->where('metode', $data['metode_pembayaran'])->where('aktif', true)->exists();
            abort_unless($methodActive, 422, 'Metode pembayaran sedang tidak aktif.');

            $total = 0;
            $rows = [];
            foreach ($data['items'] as $item) {
                $product = ProdukPupuk::whereKey($item['id'])->lockForUpdate()->firstOrFail();
                $limit = BatasPupukPetani::where('id_petani', $request->user()->id)
                    ->where('id_produk_pupuk', $product->id)
                    ->where('aktif', true)
                    ->lockForUpdate()
                    ->first();
                abort_if($limit && $limit->maksimal_jumlah <= 0, 422, "Batas pembelian {$product->nama_produk} sudah habis.");
                abort_if($limit && (int) $item['jumlah'] > $limit->maksimal_jumlah, 422, "{$product->nama_produk} melebihi batas pembelian.");
                abort_if(
                            ! $limit && (int) $item['jumlah'] > (int) $product->jumlah_stok,
                            422,
                            "Stok {$product->nama_produk} tidak cukup."
                        );
                $limit?->decrement('maksimal_jumlah', (int) $item['jumlah']);
                $subtotal = (float) $product->harga * (float) $item['jumlah'];
                $total += $subtotal;
                $rows[] = [$product, (int) $item['jumlah'], $subtotal];
            }

            $order = PesananPupuk::create([
                'nomor_pesanan' => 'PPK-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'id_petani' => $request->user()->id,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'total_harga' => $total,
                'dipesan_pada' => now(),
            ]);
            foreach ($rows as [$product, $quantity, $subtotal]) {
                DetailPesananPupuk::create([
                    'id_pesanan_pupuk' => $order->id,
                    'id_produk_pupuk' => $product->id,
                    'nama_produk' => $product->nama_produk,
                    'jumlah' => $quantity,
                    'satuan' => $product->ukuran_kemasan,
                    'harga_satuan' => $product->harga,
                    'subtotal' => $subtotal,
                ]);
            }
            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'pupuk',
                'target_peran' => 'admin',
                'judul' => 'Pesanan pupuk baru',
                'pesan' => $request->user()->name.' membuat pesanan '.$order->nomor_pesanan.'.',
                'diterbitkan_pada' => now(),
            ]);

            return $order;
        });

        return response()->json(['id' => $order->id, 'message' => 'Pesanan pupuk dikirim.'], 201);
    }

    public function cancelOrder(Request $request, PesananPupuk $pesananPupuk): JsonResponse
    {
        DB::transaction(function () use ($request, $pesananPupuk) {
            $order = PesananPupuk::with('detail')
                ->lockForUpdate()
                ->findOrFail($pesananPupuk->id);

            abort_unless(
                (int) $order->id_petani === (int) $request->user()->id,
                403,
                'Pesanan pupuk ini bukan milik Anda.'
            );
            abort_unless(
                $order->status_pesanan === 'menunggu',
                422,
                'Pesanan pupuk hanya dapat dibatalkan sebelum diproses admin.'
            );

            $this->restoreFarmerFertilizerLimit($order);

            $order->update([
                'status_pesanan' => 'dibatalkan',
                'status_pembayaran' => 'dibatalkan',
            ]);

            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'pupuk',
                'target_peran' => 'admin',
                'judul' => 'Pesanan pupuk dibatalkan',
                'pesan' => $request->user()->name.' membatalkan pesanan '.$order->nomor_pesanan.'.',
                'diterbitkan_pada' => now(),
            ]);
        });

        return response()->json(['message' => 'Pesanan pupuk berhasil dibatalkan.']);
    }

    private function orders(int $userId)
    {
        return PesananPupuk::with('detail')->where('id_petani', $userId)->latest('dipesan_pada')->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'nomorPesanan' => $order->nomor_pesanan,
                'tanggal' => optional($order->dipesan_pada)->format('d M Y H:i'),
                'timestamp' => optional($order->dipesan_pada)?->timestamp,
                'metode' => ucfirst($order->metode_pembayaran),
                'status' => $order->status_pesanan,
                'bisaDibatalkan' => $order->status_pesanan === 'menunggu',
                'total' => (float) $order->total_harga,
                'items' => $order->detail->map(fn ($item) => [
                    'nama' => $item->nama_produk,
                    'jumlah' => (int) $item->jumlah,
                    'satuan' => $item->satuan,
                    'harga' => (float) $item->harga_satuan,
                ]),
            ]);
    }

    private function restoreFarmerFertilizerLimit(PesananPupuk $order): void
    {
        foreach ($order->detail as $item) {
            if (! $item->id_produk_pupuk) {
                continue;
            }

            $limit = BatasPupukPetani::where('id_petani', $order->id_petani)
                ->where('id_produk_pupuk', $item->id_produk_pupuk)
                ->lockForUpdate()
                ->first();
            $limit?->increment('maksimal_jumlah', max(0, (int) $item->jumlah));
        }
    }
}
