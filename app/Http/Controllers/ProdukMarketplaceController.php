<?php

namespace App\Http\Controllers;

use App\Models\DetailPesananMarketplace;
use App\Models\MetodePembayaran;
use App\Models\NotifikasiAplikasi;
use App\Models\PesananMarketplace;
use App\Models\ProdukMarketplace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukMarketplaceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProdukMarketplace::with('penjual')->where('aktif', true);
        if ($request->user()->peran === 'petani') {
            $query->where('id_penjual', $request->user()->id);
        }

        return response()->json($query->latest()->get()->map(fn ($item) => $this->productRow($item)));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateProduct($request);
        unset($data['gambar']);
        $data['id_penjual'] = $request->user()->id;
        $data['gambar_produk'] = $this->uploadedImage($request)
            ?? '/assets/marketplace/karung_padi_dengan_beras_dan_daun.png';
        $item = ProdukMarketplace::create($data);

        return response()->json($this->productRow($item->load('penjual')), 201);
    }

    public function update(Request $request, ProdukMarketplace $produkMarketplace): JsonResponse
    {
        abort_unless($produkMarketplace->id_penjual === $request->user()->id, 404, 'Produk marketplace tidak ditemukan.');
        $data = $this->validateProduct($request);
        $oldImage = $produkMarketplace->gambar_produk;
        unset($data['gambar']);
        if ($path = $this->uploadedImage($request)) {
            $data['gambar_produk'] = $path;
        }
        $produkMarketplace->update($data);
        if (isset($data['gambar_produk']) && $data['gambar_produk'] !== $oldImage) {
            $this->deleteStoredImage($oldImage);
        }

        return response()->json($this->productRow($produkMarketplace->fresh()->load('penjual')));
    }

    public function destroy(Request $request, ProdukMarketplace $produkMarketplace): JsonResponse
    {
        abort_unless($produkMarketplace->id_penjual === $request->user()->id, 404, 'Produk marketplace tidak ditemukan.');
        $image = $produkMarketplace->gambar_produk;
        $produkMarketplace->delete();
        $this->deleteStoredImage($image);

        return response()->json(['message' => 'Produk marketplace dihapus.']);
    }

    public function orders(Request $request): JsonResponse
    {
        $column = $request->user()->peran === 'pembeli' ? 'id_pembeli' : 'id_penjual';
        $orders = PesananMarketplace::with(['detail', 'pembeli.profilPembeli'])
            ->where($column, $request->user()->id)
            ->latest('dipesan_pada')
            ->get();

        return response()->json($orders->map(fn ($order) => $this->orderRow($order)));
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_produk' => ['required', 'integer', 'exists:produk_marketplace,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'transfer', 'qris'])],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $marketplaceStatus = DB::table('pengaturan_aplikasi')->where('id', 1)->value('status_marketplace') ?? 'aktif';
            abort_unless($marketplaceStatus === 'aktif', 422, 'Marketplace pembeli sedang tidak aktif.');
            $methodActive = MetodePembayaran::where('konteks', 'marketplace_pembeli')
                ->where('metode', $data['metode_pembayaran'])
                ->where('aktif', true)
                ->exists();
            abort_unless($methodActive, 422, 'Metode pembayaran sedang tidak aktif.');

            $product = ProdukMarketplace::whereKey($data['id_produk'])->lockForUpdate()->firstOrFail();
            abort_if(! $product->aktif || (int) $product->jumlah_stok < (int) $data['jumlah'], 422, 'Stok produk tidak mencukupi.');
            abort_if($product->id_penjual === $request->user()->id, 422, 'Anda tidak dapat membeli produk sendiri.');

            $total = (float) $product->harga * (int) $data['jumlah'];
            $order = PesananMarketplace::create([
                'nomor_pesanan' => 'MKT-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'id_pembeli' => $request->user()->id,
                'id_penjual' => $product->id_penjual,
                'nama_pembeli_snapshot' => $request->user()->name,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'catatan_pembeli' => filled($data['catatan'] ?? null) ? trim($data['catatan']) : null,
                'total_harga' => $total,
                'dipesan_pada' => now(),
            ]);
            DetailPesananMarketplace::create([
                'id_pesanan_marketplace' => $order->id,
                'id_produk_marketplace' => $product->id,
                'nama_produk' => $product->nama_produk,
                'jumlah' => (int) $data['jumlah'],
                'satuan' => $product->satuan,
                'harga_satuan' => $product->harga ?? 0,
                'subtotal' => $total,
            ]);
            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'transaksi',
                'target_peran' => 'khusus',
                'judul' => 'Permintaan pembelian '.$product->nama_produk,
                'pesan' => $request->user()->name.' ingin membeli '.$data['jumlah'].' '.$product->satuan.'.',
                'tautan' => '/marketplace',
                'data_tambahan' => ['id_pesanan_marketplace' => $order->id, 'id_pengguna' => $product->id_penjual],
                'diterbitkan_pada' => now(),
            ]);
            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'transaksi',
                'target_peran' => 'khusus',
                'judul' => 'Pesanan menunggu persetujuan',
                'pesan' => 'Pesanan '.$product->nama_produk.' sedang menunggu persetujuan petani.',
                'tautan' => '/pembeli/riwayat-belanja',
                'data_tambahan' => ['id_pesanan_marketplace' => $order->id, 'id_pengguna' => $request->user()->id],
                'diterbitkan_pada' => now(),
            ]);

            return $order->load('detail');
        });

        return response()->json($this->orderRow($order), 201);
    }

    public function updateOrder(Request $request, PesananMarketplace $pesananMarketplace): JsonResponse
    {
        abort_unless($pesananMarketplace->id_penjual === $request->user()->id, 404, 'Pesanan marketplace tidak ditemukan.');
        $data = $request->validate([
            'status' => ['required', Rule::in(['disetujui', 'ditolak', 'selesai', 'dibatalkan'])],
        ]);

        DB::transaction(function () use ($pesananMarketplace, $data, $request) {
            $order = PesananMarketplace::with('detail')->whereKey($pesananMarketplace->id)->lockForUpdate()->firstOrFail();
            $transitions = [
                'menunggu' => ['disetujui', 'ditolak', 'dibatalkan'],
                'disetujui' => ['selesai', 'dibatalkan'],
                'ditolak' => [],
                'selesai' => [],
                'dibatalkan' => [],
            ];
            abort_unless(
                in_array($data['status'], $transitions[$order->status_pesanan] ?? [], true),
                422,
                'Perubahan status pesanan tidak valid.'
            );

            if ($data['status'] === 'disetujui') {
                foreach ($order->detail as $detail) {
                    if (! $detail->id_produk_marketplace) {
                        continue;
                    }
                    $product = ProdukMarketplace::whereKey($detail->id_produk_marketplace)->lockForUpdate()->first();
                    abort_if(! $product || (int) $product->jumlah_stok < (int) $detail->jumlah, 422, 'Stok produk tidak mencukupi.');
                    $product->decrement('jumlah_stok', $detail->jumlah);
                }
            }

            if ($data['status'] === 'dibatalkan' && $order->status_pesanan === 'disetujui') {
                foreach ($order->detail as $detail) {
                    if ($detail->id_produk_marketplace) {
                        ProdukMarketplace::whereKey($detail->id_produk_marketplace)
                            ->lockForUpdate()
                            ->increment('jumlah_stok', $detail->jumlah);
                    }
                }
            }

            $order->update([
                'status_pesanan' => $data['status'],
                'status_pembayaran' => match ($data['status']) {
                    'selesai' => 'lunas',
                    'ditolak', 'dibatalkan' => 'dibatalkan',
                    default => $order->status_pembayaran,
                },
                'dikonfirmasi_pada' => $order->dikonfirmasi_pada ?? now(),
                'diselesaikan_pada' => $data['status'] === 'selesai' ? now() : null,
            ]);
            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'transaksi',
                'target_peran' => 'khusus',
                'judul' => 'Status pesanan '.$order->nomor_pesanan,
                'pesan' => 'Pesanan Anda '.$data['status'].'.',
                'tautan' => '/pembeli/riwayat-belanja',
                'data_tambahan' => ['id_pesanan_marketplace' => $order->id, 'id_pengguna' => $order->id_pembeli],
                'diterbitkan_pada' => now(),
            ]);
        });

        return response()->json(['message' => 'Status pesanan diperbarui.']);
    }

    public function cancelOrderByBuyer(Request $request, PesananMarketplace $pesananMarketplace): JsonResponse
    {
        abort_unless($pesananMarketplace->id_pembeli === $request->user()->id, 404, 'Pesanan marketplace tidak ditemukan.');

        DB::transaction(function () use ($pesananMarketplace, $request) {
            $order = PesananMarketplace::whereKey($pesananMarketplace->id)->lockForUpdate()->firstOrFail();
            abort_unless(
                $order->status_pesanan === 'menunggu',
                422,
                'Pesanan hanya dapat dibatalkan sebelum disetujui petani.'
            );

            $order->update([
                'status_pesanan' => 'dibatalkan',
                'status_pembayaran' => 'dibatalkan',
            ]);
            NotifikasiAplikasi::create([
                'dibuat_oleh' => $request->user()->id,
                'kategori' => 'transaksi',
                'target_peran' => 'khusus',
                'judul' => 'Pesanan dibatalkan oleh pembeli',
                'pesan' => $request->user()->name.' membatalkan pesanan '.$order->nomor_pesanan.'.',
                'tautan' => '/marketplace',
                'data_tambahan' => ['id_pesanan_marketplace' => $order->id, 'id_pengguna' => $order->id_penjual],
                'diterbitkan_pada' => now(),
            ]);
        });

        return response()->json(['message' => 'Pesanan berhasil dibatalkan.']);
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'nama_produk' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'alamat_produk' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'jumlah_stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:30'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.max' => 'Nama produk maksimal 150 karakter.',
            'harga.required' => 'Harga produk wajib diisi.',
            'harga.numeric' => 'Harga produk harus berupa angka.',
            'harga.min' => 'Harga produk tidak boleh kurang dari nol.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh kurang dari nol.',
            'satuan.required' => 'Satuan stok wajib diisi.',
            'satuan.max' => 'Satuan stok maksimal 30 karakter.',
            'gambar.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya tidak melebihi 2 MB.',
            'gambar.image' => 'File yang dipilih harus berupa foto.',
            'gambar.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }

    private function productRow(ProdukMarketplace $item): array
    {
        return [
            'id' => $item->id,
            'nama' => $item->nama_produk,
            'deskripsi' => $item->deskripsi,
            'alamat' => $item->alamat_produk,
            'harga' => (float) $item->harga,
            'jumlah' => (int) $item->jumlah_stok,
            'satuan' => $item->satuan,
            'gambar' => $item->gambar_produk,
            'petani' => $item->penjual?->name ?? 'Petani Lokal',
            'idPenjual' => $item->id_penjual,
        ];
    }

    private function orderRow(PesananMarketplace $order): array
    {
        $item = $order->detail->first();
        $buyer = $order->pembeli;

        return [
            'id' => $order->id,
            'nomorPesanan' => $order->nomor_pesanan,
            'namaPembeli' => $order->nama_pembeli_snapshot,
            'nomorHpPembeli' => $buyer?->nomor_hp ?? '-',
            'alamatPembeli' => $buyer?->alamat ?: ($buyer?->profilPembeli?->alamat_gudang ?: '-'),
            'namaGudangPembeli' => $buyer?->profilPembeli?->nama_gudang ?? '',
            'produk' => $item?->nama_produk ?? 'Produk',
            'jumlah' => (int) ($item?->jumlah ?? 0),
            'satuan' => $item?->satuan ?? 'kg',
            'catatan' => $order->catatan_pembeli,
            'metodePembayaran' => ucfirst($order->metode_pembayaran),
            'totalBayar' => (float) $order->total_harga,
            'waktu' => optional($order->dipesan_pada)->format('d M H:i'),
            'tanggal' => optional($order->dipesan_pada)->format('Y-m-d'),
            'timestamp' => optional($order->dipesan_pada)?->timestamp,
            'status' => $order->status_pesanan,
        ];
    }

    private function uploadedImage(Request $request): ?string
    {
        return $request->hasFile('gambar')
            ? '/storage/'.$request->file('gambar')->store('poktan/marketplace', 'public')
            : null;
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && Str::startsWith($path, '/storage/')) {
            Storage::disk('public')->delete(Str::after($path, '/storage/'));
        }
    }
}
