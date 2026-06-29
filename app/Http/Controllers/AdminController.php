<?php

namespace App\Http\Controllers;

use App\Models\BatasPupukPetani;
use App\Models\JadwalTanam;
use App\Models\KontenAplikasi;
use App\Models\LahanPetani;
use App\Models\MetodePembayaran;
use App\Models\NotifikasiAplikasi;
use App\Models\PengaturanAplikasi;
use App\Models\PesananMarketplace;
use App\Models\PesananPupuk;
use App\Models\ProdukMarketplace;
use App\Models\ProdukPupuk;
use App\Models\ProfilPembeli;
use App\Models\ProfilPetani;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function bootstrap(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'petani' => User::where('peran', 'petani')->count(),
                'pembeli' => User::where('peran', 'pembeli')->count(),
                'menunggu' => User::whereIn('peran', ['petani', 'pembeli'])->where('status', 'menunggu')->count(),
                'produk' => ProdukMarketplace::count(),
                'pupuk' => ProdukPupuk::count(),
                'pesanan' => PesananPupuk::count(),
            ],
            'activities' => $this->activityRows(),
            'users' => $this->userRows(),
            'fertilizers' => ProdukPupuk::latest()->get()->map(fn ($item) => $this->fertilizerRow($item)),
            'orders' => $this->fertilizerOrders(),
            'notifications' => $this->adminNotifications()->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->judul,
                'category' => $this->categoryLabel($item->kategori),
                'message' => $item->pesan,
                'target' => ucfirst($item->target_peran),
                'createdAt' => optional($item->created_at)->toISOString(),
            ]),
            'contents' => KontenAplikasi::latest()->get()->map(fn ($item) => $this->contentRow($item)),
            'settings' => $this->settingsPayload(),
            'planting' => $this->plantingRows(),
        ]);
    }

    public function storeUser(Request $request): JsonResponse
    {
        $data = $this->validateUser($request);

        $user = DB::transaction(function () use ($data) {
            $user = User::create($this->userPayload($data));
            $this->syncUserProfile($user, $data);

            return $user;
        });

        return response()->json($this->userRow($user->fresh()), 201);
    }

    public function updateUser(Request $request, User $user): JsonResponse
    {
        abort_if($user->peran === 'admin', 422, 'Akun admin tidak dapat diubah dari daftar pengguna.');
        $data = $this->validateUser($request, $user);

        DB::transaction(function () use ($user, $data) {
            $payload = $this->userPayload($data, $user);
            if (blank($data['password'] ?? null)) {
                unset($payload['password'], $payload['password_updated_at']);
            }
            $user->update($payload);
            $this->syncUserProfile($user, $data);
        });

        return response()->json($this->userRow($user->fresh()));
    }

    public function destroyUser(User $user): JsonResponse
    {
        abort_if($user->peran === 'admin', 422, 'Akun admin tidak dapat dihapus.');
        $photo = $user->foto_profil;

        DB::transaction(function () use ($user) {
            if ($user->peran === 'petani') {
                $this->syncFertilizerLimits($user, []);
            }

            $user->delete();
        });

        $this->deleteStoredImage($photo);

        return response()->json(['message' => 'Pengguna dihapus.']);
    }

    public function storeFertilizer(Request $request): JsonResponse
    {
        $data = $this->validateFertilizer($request);
        unset($data['gambar'], $data['remove_image']);
        $data['slug'] = $this->uniqueSlug($data['nama_produk']);
        $data['dibuat_oleh'] = $request->user()->id;
        $data['gambar_produk'] = $this->uploadedImage($request, 'gambar', 'pupuk')
            ?? '/assets/pupuk/tas_pupuk_urea_dengan_granula.png';
        $item = ProdukPupuk::create($data);

        return response()->json($this->fertilizerRow($item), 201);
    }

    public function updateFertilizer(Request $request, ProdukPupuk $produkPupuk): JsonResponse
    {
        $data = $this->validateFertilizer($request, $produkPupuk);
        $removeImage = (bool) ($data['remove_image'] ?? false);
        $oldImage = $produkPupuk->gambar_produk;
        unset($data['gambar'], $data['remove_image']);
        if ($produkPupuk->nama_produk !== $data['nama_produk']) {
            $data['slug'] = $this->uniqueSlug($data['nama_produk'], $produkPupuk->id);
        }
        if ($path = $this->uploadedImage($request, 'gambar', 'pupuk')) {
            $data['gambar_produk'] = $path;
        } elseif ($removeImage) {
            $data['gambar_produk'] = '/assets/pupuk/tas_pupuk_urea_dengan_granula.png';
        }
        $produkPupuk->update($data);
        if (isset($data['gambar_produk']) && $data['gambar_produk'] !== $oldImage) {
            $this->deleteStoredImage($oldImage);
        }

        return response()->json($this->fertilizerRow($produkPupuk->fresh()));
    }

    public function destroyFertilizer(ProdukPupuk $produkPupuk): JsonResponse
    {
        $image = $produkPupuk->gambar_produk;
        $produkPupuk->delete();
        $this->deleteStoredImage($image);

        return response()->json(['message' => 'Produk pupuk dihapus.']);
    }

    public function updateFertilizerOrder(Request $request, PesananPupuk $pesananPupuk): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'diterima', 'ditolak', 'selesai', 'dibatalkan'])],
        ]);

        $quotaRestored = DB::transaction(function () use ($data, $pesananPupuk) {
            $order = PesananPupuk::with('detail')
                ->lockForUpdate()
                ->findOrFail($pesananPupuk->id);
            $transitions = [
                'menunggu' => ['diterima', 'ditolak', 'dibatalkan'],
                'diterima' => ['selesai', 'dibatalkan'],
                'ditolak' => [],
                'selesai' => [],
                'dibatalkan' => [],
            ];
            abort_unless(
                in_array($data['status'], $transitions[$order->status_pesanan] ?? [], true),
                422,
                'Perubahan status pesanan tidak valid.'
            );

            if (in_array($data['status'], ['ditolak', 'dibatalkan'], true)) {
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

            $paymentStatus = match ($data['status']) {
                'selesai' => 'lunas',
                'ditolak', 'dibatalkan' => 'dibatalkan',
                default => $order->status_pembayaran,
            };
            $order->update([
                'status_pesanan' => $data['status'],
                'status_pembayaran' => $paymentStatus,
                'dikonfirmasi_pada' => $order->dikonfirmasi_pada ?? now(),
                'diselesaikan_pada' => $data['status'] === 'selesai' ? now() : null,
            ]);

            return in_array($data['status'], ['ditolak', 'dibatalkan'], true);
        });

        return response()->json([
            'message' => $quotaRestored
                ? 'Pesanan ditolak dan batas pembelian pupuk dikembalikan.'
                : 'Status pesanan diperbarui.',
        ]);
    }

    public function destroyFertilizerOrder(PesananPupuk $pesananPupuk): JsonResponse
    {
        $pesananPupuk->delete();

        return response()->json(['message' => 'Pesanan pupuk dihapus.']);
    }

    public function storeNotification(Request $request): JsonResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:180'],
            'kategori' => ['required', Rule::in(['transaksi', 'pupuk', 'cuaca', 'edukasi', 'hama_penyakit', 'sistem'])],
            'pesan' => ['required', 'string'],
            'target_peran' => ['required', Rule::in(['semua', 'admin', 'petani', 'pembeli'])],
        ]);
        $item = NotifikasiAplikasi::create([
            ...$data,
            'dibuat_oleh' => $request->user()->id,
            'target_peran' => $data['target_peran'] ?? 'semua',
            'diterbitkan_pada' => now(),
        ]);

        return response()->json(['id' => $item->id], 201);
    }

    public function destroyNotification(NotifikasiAplikasi $notifikasi): JsonResponse
    {
        $notifikasi->delete();

        return response()->json(['message' => 'Notifikasi dihapus.']);
    }

    public function storeContent(Request $request): JsonResponse
    {
        $data = $this->validateContent($request);
        unset($data['gambar'], $data['remove_image']);
        $data['slug'] = $this->uniqueContentSlug($data['judul']);
        $data['dibuat_oleh'] = $request->user()->id;
        $data['gambar'] = $this->uploadedImage($request, 'gambar', 'konten')
            ?? $this->defaultContentImage($data['kategori']);
        $data['diterbitkan_pada'] = now();
        $item = KontenAplikasi::create($data);

        return response()->json($this->contentRow($item), 201);
    }

    public function updateContent(Request $request, KontenAplikasi $konten): JsonResponse
    {
        $data = $this->validateContent($request);
        $removeImage = (bool) ($data['remove_image'] ?? false);
        $oldImage = $konten->gambar;
        unset($data['gambar'], $data['remove_image']);
        if ($konten->judul !== $data['judul']) {
            $data['slug'] = $this->uniqueContentSlug($data['judul'], $konten->id);
        }
        if ($path = $this->uploadedImage($request, 'gambar', 'konten')) {
            $data['gambar'] = $path;
        } elseif ($removeImage) {
            $data['gambar'] = $this->defaultContentImage($data['kategori']);
        }
        $konten->update($data);
        if (array_key_exists('gambar', $data) && $data['gambar'] !== $oldImage) {
            $this->deleteStoredImage($oldImage);
        }

        return response()->json($this->contentRow($konten->fresh()));
    }

    public function destroyContent(KontenAplikasi $konten): JsonResponse
    {
        $image = $konten->gambar;
        $konten->delete();
        $this->deleteStoredImage($image);

        return response()->json(['message' => 'Konten dihapus.']);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->validate([
            'appName' => ['required', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:255'],
            'marketplace' => ['required', Rule::in(['Aktif', 'Perawatan', 'Nonaktif'])],
            'maintenance' => ['required', Rule::in(['Aktif', 'Nonaktif'])],
            'maintenanceMessage' => ['required', 'string', 'max:2000'],
            'buyerPaymentDisabledMethods' => ['array'],
            'buyerPaymentDisabledMethods.*' => [Rule::in(['Tunai', 'Transfer', 'QRIS'])],
            'farmerPaymentDisabledMethods' => ['array'],
            'farmerPaymentDisabledMethods.*' => [Rule::in(['Tunai', 'Transfer', 'QRIS'])],
        ]);

        DB::transaction(function () use ($request, $data) {
            PengaturanAplikasi::updateOrCreate(
                ['id' => 1],
                [
                    'nama_aplikasi' => $data['appName'],
                    'lokasi_aplikasi' => $data['location'] ?? null,
                    'status_marketplace' => Str::lower($data['marketplace']),
                    'maintenance_aktif' => $data['maintenance'] === 'Aktif',
                    'pesan_maintenance' => $data['maintenanceMessage'],
                    'updated_by' => $request->user()->id,
                ]
            );

            foreach ([
                'marketplace_pembeli' => $data['buyerPaymentDisabledMethods'] ?? [],
                'pupuk_petani' => $data['farmerPaymentDisabledMethods'] ?? [],
            ] as $context => $disabled) {
                foreach (['Tunai', 'Transfer', 'QRIS'] as $method) {
                    MetodePembayaran::updateOrCreate(
                        ['konteks' => $context, 'metode' => Str::lower($method)],
                        ['nama_tampilan' => $method, 'aktif' => ! in_array($method, $disabled, true)]
                    );
                }
            }
        });

        return response()->json($this->settingsPayload());
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);
        $request->user()->update([
            'password' => $data['password'],
            'password_updated_at' => now(),
        ]);

        return response()->json(['message' => 'Password admin diperbarui.']);
    }

    public function clearFertilizers(Request $request): JsonResponse
    {
        $this->confirmAdminPassword($request);
        $images = ProdukPupuk::withTrashed()->pluck('gambar_produk');
        ProdukPupuk::withTrashed()->forceDelete();
        $images->each(fn ($path) => $this->deleteStoredImage($path));

        return response()->json(['message' => 'Seluruh produk pupuk dihapus dari database.']);
    }

    public function clearFertilizerOrders(Request $request): JsonResponse
    {
        $this->confirmAdminPassword($request);
        PesananPupuk::withTrashed()->forceDelete();

        return response()->json(['message' => 'Seluruh pesanan pupuk dihapus dari database.']);
    }

    public function clearAdminData(Request $request): JsonResponse
    {
        $this->confirmAdminPassword($request);
        $storedImages = ProdukPupuk::withTrashed()->pluck('gambar_produk')
            ->merge(KontenAplikasi::withTrashed()->pluck('gambar'))
            ->merge(ProdukMarketplace::withTrashed()->pluck('gambar_produk'))
            ->merge(User::where('peran', '!=', 'admin')->withTrashed()->pluck('foto_profil'))
            ->filter()
            ->unique()
            ->values();

        DB::transaction(function () use ($request) {
            PesananMarketplace::withTrashed()->forceDelete();
            PesananPupuk::withTrashed()->forceDelete();
            ProdukMarketplace::withTrashed()->forceDelete();
            ProdukPupuk::withTrashed()->forceDelete();
            NotifikasiAplikasi::withTrashed()->forceDelete();
            KontenAplikasi::withTrashed()->forceDelete();
            User::where('peran', '!=', 'admin')->withTrashed()->forceDelete();

            DB::table('pengaturan_aplikasi')->updateOrInsert(
                ['id' => 1],
                [
                    'nama_aplikasi' => 'POKTAN Lancang Kuning',
                    'lokasi_aplikasi' => 'Lancang Kuning',
                    'status_marketplace' => 'aktif',
                    'maintenance_aktif' => false,
                    'pesan_maintenance' => 'Aplikasi sedang dalam perawatan. Silakan coba lagi nanti.',
                    'updated_by' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            MetodePembayaran::query()->update(['aktif' => true]);
        });
        $storedImages->each(fn ($path) => $this->deleteStoredImage($path));

        return response()->json(['message' => 'Data operasional admin dikosongkan. Akun admin tetap dipertahankan.']);
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'regex:/^08[0-9]{8,13}$/', Rule::unique('users', 'nomor_hp')->ignore($user?->id)],
            'role' => ['required', Rule::in(['Petani', 'Pembeli'])],
            'nik' => [
                Rule::requiredIf($request->input('role') === 'Petani'),
                'nullable',
                'digits:16',
                Rule::unique('users', 'nik')->ignore($user?->id),
            ],
            'warehouseName' => [Rule::requiredIf($request->input('role') === 'Pembeli'), 'nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string'],
            'landAreaMeter' => ['nullable', 'integer', 'min:0'],
            'fertilizerLimits' => ['nullable', 'array'],
            'fertilizerLimits.*' => ['nullable', 'integer', 'min:0'],
            'status' => [
                Rule::requiredIf($request->input('role') === 'Petani'),
                'nullable',
                Rule::in(['Aktif', 'Menunggu', 'Nonaktif']),
            ],
            'password' => [$user ? 'nullable' : 'required', 'nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);
    }

    private function confirmAdminPassword(Request $request): void
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);
    }

    private function userPayload(array $data, ?User $user = null): array
    {
        $role = Str::lower($data['role']);
        $payload = [
            'name' => $data['name'],
            'username' => $role === 'petani' ? $data['nik'] : $data['phone'],
            'nomor_hp' => $data['phone'],
            'nik' => $role === 'petani' ? $data['nik'] : null,
            'alamat' => $data['address'] ?? null,
            'peran' => $role,
            'status' => $role === 'pembeli' ? 'aktif' : Str::lower($data['status']),
        ];
        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
            $payload['password_updated_at'] = now();
        }

        return $payload;
    }

    private function syncUserProfile(User $user, array $data): void
    {
        if ($user->peran === 'petani') {
            ProfilPembeli::where('id_pengguna', $user->id)->delete();
            ProfilPetani::updateOrCreate(
                ['id_pengguna' => $user->id],
                ['luas_lahan_meter' => (int) ($data['landAreaMeter'] ?? 0)]
            );
            LahanPetani::updateOrCreate(
                ['id_petani' => $user->id, 'nama_lahan' => 'Lahan Padi'],
                [
                    'nama_pemilik' => $user->name,
                    'luas_meter' => (int) ($data['landAreaMeter'] ?? 0),
                    'alamat' => $data['address'] ?? null,
                    'status' => 'aktif',
                ]
            );
            $this->syncFertilizerLimits($user, $data['fertilizerLimits'] ?? []);
        } else {
            $this->syncFertilizerLimits($user, []);
            ProfilPetani::where('id_pengguna', $user->id)->delete();
            LahanPetani::where('id_petani', $user->id)->delete();
            ProfilPembeli::updateOrCreate(
                ['id_pengguna' => $user->id],
                ['nama_gudang' => $data['warehouseName'], 'alamat_gudang' => $data['address'] ?? null]
            );
        }
    }

    private function syncFertilizerLimits(User $user, array $requestedLimits): void
    {
        $newLimits = collect($requestedLimits)
            ->mapWithKeys(fn ($limit, $productId) => [(int) $productId => max(0, (int) $limit)])
            ->filter(fn ($limit) => $limit > 0);

        $oldLimits = BatasPupukPetani::where('id_petani', $user->id)
            ->lockForUpdate()
            ->get()
            ->keyBy('id_produk_pupuk')
            ->map(fn ($limit) => (int) $limit->maksimal_jumlah);

        $productIds = $oldLimits->keys()
            ->merge($newLimits->keys())
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($productIds->isEmpty()) {
            BatasPupukPetani::where('id_petani', $user->id)->delete();

            return;
        }

        $products = ProdukPupuk::whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($productIds as $productId) {
            $product = $products->get($productId);
            $oldLimit = (int) ($oldLimits->get($productId) ?? 0);
            $newLimit = (int) ($newLimits->get($productId) ?? 0);

            if (! $product) {
                BatasPupukPetani::where('id_petani', $user->id)
                    ->where('id_produk_pupuk', $productId)
                    ->delete();
                continue;
            }

            $difference = $newLimit - $oldLimit;

            if ($difference > 0) {
                abort_if(
                    (int) $product->jumlah_stok < $difference,
                    422,
                    "Stok {$product->nama_produk} tidak cukup untuk menambah batas. Sisa stok: ".number_format((int) $product->jumlah_stok, 0, ',', '.').' karung.'
                );

                $product->decrement('jumlah_stok', $difference);
            } elseif ($difference < 0) {
                $product->increment('jumlah_stok', abs($difference));
            }

            if ($newLimit > 0) {
                BatasPupukPetani::updateOrCreate(
                    ['id_petani' => $user->id, 'id_produk_pupuk' => $productId],
                    ['maksimal_jumlah' => $newLimit, 'aktif' => true]
                );
            } else {
                BatasPupukPetani::where('id_petani', $user->id)
                    ->where('id_produk_pupuk', $productId)
                    ->delete();
            }
        }
    }

    private function userRows()
    {
        $reportFertilizerPurchases = $this->reportFertilizerPurchases();

        return User::whereIn('peran', ['petani', 'pembeli'])
            ->with([
                'profilPetani',
                'profilPembeli',
                'lahan' => fn ($query) => $query->where('status', 'aktif'),
            ])
            ->latest()->get()->map(fn ($user) => $this->userRow(
                $user,
                $reportFertilizerPurchases[$user->id] ?? []
            ));
    }

    private function userRow(User $user, array $reportFertilizerPurchases = []): array
    {
        $activeLands = $user->relationLoaded('lahan')
            ? $user->lahan
            : $user->lahan()->where('status', 'aktif')->get();
        $limits = BatasPupukPetani::where('id_petani', $user->id)
            ->pluck('maksimal_jumlah', 'id_produk_pupuk')
            ->map(fn ($limit) => (int) $limit);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->nomor_hp,
            'role' => ucfirst($user->peran),
            'nik' => $user->nik ?? '',
            'warehouseName' => $user->profilPembeli?->nama_gudang ?? '',
            'address' => $user->alamat ?? '',
            'landAreaMeter' => (int) ($user->profilPetani?->luas_lahan_meter ?? 0),
            'rencana_tanam_ha' => round(((float) $activeLands->sum('luas_meter')) / 10000, 3),
            'fertilizerLimits' => $limits,
            'reportFertilizerPurchases' => $reportFertilizerPurchases,
            'status' => ucfirst($user->status),
            'hasPassword' => filled($user->password),
        ];
    }

    private function reportFertilizerPurchases(): array
    {
        $purchases = [];

        PesananPupuk::with('detail')
            ->whereNotIn('status_pesanan', ['ditolak', 'dibatalkan'])
            ->get()
            ->each(function (PesananPupuk $order) use (&$purchases) {
                $year = (string) optional($order->dipesan_pada ?? $order->created_at)->year;

                if (! $order->id_petani || ! $year) {
                    return;
                }

                foreach ($order->detail as $item) {
                    $group = $this->fertilizerReportGroup($item->nama_produk);

                    if (! $group) {
                        continue;
                    }

                    $quantity = max(0, (int) $item->jumlah);
                    $purchases[$order->id_petani][$year][$group] = ($purchases[$order->id_petani][$year][$group] ?? 0) + $quantity;
                }
            });

        return $purchases;
    }

    private function fertilizerReportGroup(?string $name): ?string
    {
        $normalized = Str::lower(trim((string) $name));

        return match (true) {
            str_contains($normalized, 'urea') => 'urea',
            $normalized === 'npk' || (str_contains($normalized, 'npk') && ! str_contains($normalized, 'formula') && ! str_contains($normalized, '16-16-16')) => 'npk',
            str_contains($normalized, 'npk') && (str_contains($normalized, 'formula') || str_contains($normalized, '16-16-16')) => 'npk_formula',
            str_contains($normalized, 'organik') => 'organik',
            str_contains($normalized, 'za') => 'za',
            default => null,
        };
    }

    private function validateFertilizer(Request $request, ?ProdukPupuk $produkPupuk = null): array
    {
        return $request->validate([
            'nama_produk' => [
                'required',
                'string',
                'max:150',
                Rule::unique('produk_pupuk', 'nama_produk')->whereNull('deleted_at')->ignore($produkPupuk?->id),
            ],
            'harga' => ['required', 'numeric', 'min:0'],
            'jumlah_stok' => ['required', 'integer', 'min:0'],
            'ukuran_kemasan' => ['required', 'string', 'max:80'],
            'deskripsi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'max:1536'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }

    private function fertilizerRow(ProdukPupuk $item): array
    {
        return [
            'id' => $item->id,
            'nama' => $item->nama_produk,
            'harga' => (float) $item->harga,
            'stok' => (int) $item->jumlah_stok,
            'package' => $item->ukuran_kemasan ?: 'paket',
            'satuan' => $item->ukuran_kemasan ? '/ '.$item->ukuran_kemasan : '/ paket',
            'deskripsi' => $item->deskripsi,
            'gambar' => $item->gambar_produk,
        ];
    }

    private function fertilizerOrders()
    {
        return PesananPupuk::with(['detail', 'petani'])->latest('dipesan_pada')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'tanggal' => optional($order->dipesan_pada)->format('d M Y H:i'),
                'metode' => ucfirst($order->metode_pembayaran),
                'petani' => $order->petani?->name ?? 'Petani',
                'status' => $order->status_pesanan,
                'statusLabel' => ucfirst($order->status_pesanan),
                'paymentStatus' => $order->status_pembayaran,
                'paymentStatusLabel' => ucfirst($order->status_pembayaran),
                'total' => (float) $order->total_harga,
                'items' => $order->detail->map(fn ($item) => [
                    'nama' => $item->nama_produk,
                    'jumlah' => (int) $item->jumlah,
                    'harga' => (float) $item->harga_satuan,
                ]),
            ];
        });
    }

    private function validateContent(Request $request): array
    {
        return $request->validate([
            'kategori' => ['required', Rule::in(['edukasi', 'hama_penyakit'])],
            'judul' => ['required', 'string', 'max:200'],
            'jenis_konten' => ['required', Rule::in(['artikel', 'video', 'panduan', 'solusi'])],
            'deskripsi' => ['required', 'string'],
            'tautan' => [
                'required',
                'string',
                'max:500',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! Str::startsWith($value, '/') && ! filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Link tujuan harus berupa URL lengkap atau path aplikasi yang diawali /.');
                    }

                    if (filter_var($value, FILTER_VALIDATE_URL)
                        && ! in_array(parse_url($value, PHP_URL_SCHEME), ['http', 'https'], true)) {
                        $fail('Link tujuan hanya mendukung protokol HTTP atau HTTPS.');
                    }
                },
            ],
            'gambar' => ['nullable', 'image', 'max:1536'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }

    private function contentRow(KontenAplikasi $item): array
    {
        return [
            'id' => $item->id,
            'category' => $item->kategori === 'hama_penyakit' ? 'Hama & Penyakit' : 'Edukasi',
            'title' => $item->judul,
            'type' => ucfirst($item->jenis_konten),
            'description' => $item->deskripsi,
            'image' => $item->gambar,
            'link' => $item->tautan,
            'createdAt' => optional($item->created_at)->toISOString(),
        ];
    }

    private function defaultContentImage(string $category): string
    {
        return $category === 'hama_penyakit'
            ? '/assets/hama-penyakit/hama.png'
            : '/assets/edukasi/orang-edukasi.png';
    }

    private function settingsPayload(): array
    {
        $settings = DB::table('pengaturan_aplikasi')->where('id', 1)->first();
        $methods = MetodePembayaran::all()->groupBy('konteks');
        $disabled = fn ($context) => collect($methods->get($context, []))
            ->where('aktif', false)->pluck('nama_tampilan')->values()->all();

        return [
            'appName' => $settings->nama_aplikasi ?? 'POKTAN Lancang Kuning',
            'location' => $settings->lokasi_aplikasi ?? 'Lancang Kuning',
            'marketplace' => ucfirst($settings->status_marketplace ?? 'aktif'),
            'buyerPaymentDisabledMethods' => $disabled('marketplace_pembeli'),
            'farmerPaymentDisabledMethods' => $disabled('pupuk_petani'),
            'maintenance' => ($settings->maintenance_aktif ?? false) ? 'Aktif' : 'Nonaktif',
            'maintenanceMessage' => $settings->pesan_maintenance
                ?? 'Aplikasi sedang dalam perawatan. Silakan coba lagi nanti.',
        ];
    }

    private function plantingRows()
    {
        return User::where('peran', 'petani')->with('profilPetani')->get()->map(function ($farmer) {
            $schedule = JadwalTanam::where('id_petani', $farmer->id)->latest()->first();

            return [
                'id' => $farmer->id,
                'name' => $farmer->name,
                'phone' => $farmer->nomor_hp,
                'landAreaHa' => round(((float) ($farmer->profilPetani?->luas_lahan_meter ?? 0)) / 10000, 3),
                'activeStage' => $schedule ? Str::headline($schedule->tahap_aktif) : 'Pembibitan',
                'completed' => $schedule?->jumlah_tahap_selesai ?? 0,
                'percent' => (int) ($schedule?->persentase_progres ?? 0),
                'seedDate' => optional($schedule?->tanggal_semai)->format('Y-m-d'),
                'completedAt' => optional($schedule?->diselesaikan_pada)->format('Y-m-d'),
                'status' => ucfirst($schedule?->status ?? 'belum dimulai'),
            ];
        });
    }

    private function activityRows()
    {
        $users = User::whereIn('peran', ['petani', 'pembeli'])->latest()->limit(5)->get()->map(fn ($user) => [
            'description' => $user->name.' terdaftar sebagai '.ucfirst($user->peran),
            'category' => 'Pengguna',
            'status' => ucfirst($user->status),
            'occurredAt' => $user->created_at,
        ]);
        $orders = PesananPupuk::latest('dipesan_pada')->limit(5)->get()->map(fn ($order) => [
            'description' => 'Pesanan pupuk '.$order->nomor_pesanan,
            'category' => 'Pupuk',
            'status' => ucfirst($order->status_pesanan),
            'occurredAt' => $order->dipesan_pada,
        ]);
        $notifications = $this->adminNotifications()->take(5)->map(fn ($item) => [
            'description' => $item->judul,
            'category' => 'Notifikasi',
            'status' => 'Terbit',
            'occurredAt' => $item->diterbitkan_pada ?? $item->created_at,
        ]);
        $contents = KontenAplikasi::latest()->limit(5)->get()->map(fn ($item) => [
            'description' => $item->judul,
            'category' => 'Konten',
            'status' => ucfirst($item->status),
            'occurredAt' => $item->diterbitkan_pada ?? $item->created_at,
        ]);

        return $users
            ->concat($orders)
            ->concat($notifications)
            ->concat($contents)
            ->sortByDesc('occurredAt')
            ->take(10)
            ->values()
            ->map(fn ($item) => [
                ...$item,
                'occurredAt' => optional($item['occurredAt'])->toISOString(),
            ]);
    }

    private function adminNotifications()
    {
        return NotifikasiAplikasi::latest()
            ->get()
            ->reject(fn ($item) => $this->isMarketplacePurchaseNotification($item))
            ->values();
    }

    private function isMarketplacePurchaseNotification(NotifikasiAplikasi $notification): bool
    {
        return filled(data_get($notification->data_tambahan, 'id_pesanan_marketplace'));
    }

    private function uniqueSlug(string $name, ?int $ignore = null): string
    {
        return $this->makeUniqueSlug(ProdukPupuk::withTrashed(), $name, $ignore);
    }

    private function uniqueContentSlug(string $name, ?int $ignore = null): string
    {
        return $this->makeUniqueSlug(KontenAplikasi::withTrashed(), $name, $ignore);
    }

    private function makeUniqueSlug($query, string $name, ?int $ignore): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $suffix = 2;
        while ((clone $query)->when($ignore, fn ($q) => $q->where('id', '!=', $ignore))->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function uploadedImage(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return '/storage/'.$request->file($field)->store("poktan/{$folder}", 'public');
    }

    private function deleteStoredImage(?string $path): void
    {
        if (! $path || ! Str::startsWith($path, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($path, '/storage/'));
    }

    private function categoryLabel(string $category): string
    {
        return match ($category) {
            'hama_penyakit' => 'Hama & Penyakit',
            default => ucfirst($category),
        };
    }
}
