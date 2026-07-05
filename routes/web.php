<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HasilPanenController;
use App\Http\Controllers\JadwalTanamController;
use App\Http\Controllers\KontenAplikasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProdukMarketplaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PupukController;
use App\Models\LahanPetani;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController; // ← tambahkan ini

Route::get('/', [LandingController::class, 'index'])->name('landing');
/*
Route::get('/', function () {
    return view('landing.index');
})->name('landing');

Route::view('/', 'landing.index');

Route::view('/landing', 'landing.index');
*/

/*
Route::get('/', function () {
    return auth()->check() ? redirect()->route(match (auth()->user()->peran) {
        'admin' => 'admin',
        'pembeli' => 'pembeli.marketplace',
        default => 'dashboard',
    }) : redirect()->route('login');
});
*/

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])
    ->middleware('throttle:login')
    ->name('login.store');

Route::middleware('guest')->group(function () {
    //Route::view('/daftar', 'daftar')->name('daftar');
    Route::get('/daftar', [AuthController::class, 'createPetani'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'registerPetani'])
        ->middleware('throttle:registration')
        ->name('daftar.store');
    Route::view('/daftar-pembeli', 'daftar-pembeli')->name('daftar-pembeli');
    Route::post('/daftar-pembeli', [AuthController::class, 'registerPembeli'])
        ->middleware('throttle:registration')
        ->name('daftar-pembeli.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin', [
                'kelompokTani' => \App\Models\KelompokTani::where('aktif', true)
                                    ->orderBy('nama')
                                    ->get(),
            ]);
        })->name('admin');    

    Route::prefix('api/admin')->middleware('throttle:api')->group(function () {
        Route::get('/bootstrap', [AdminController::class, 'bootstrap']);
        Route::post('/pengguna', [AdminController::class, 'storeUser']);
        Route::put('/pengguna/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/pengguna/{user}', [AdminController::class, 'destroyUser']);
        Route::post('/pupuk', [AdminController::class, 'storeFertilizer']);
        Route::post('/pupuk/{produkPupuk}', [AdminController::class, 'updateFertilizer']);
        Route::delete('/pupuk/{produkPupuk}', [AdminController::class, 'destroyFertilizer']);
        Route::patch('/pesanan-pupuk/{pesananPupuk}', [AdminController::class, 'updateFertilizerOrder']);
        Route::delete('/pesanan-pupuk/{pesananPupuk}', [AdminController::class, 'destroyFertilizerOrder']);
        Route::post('/notifikasi', [AdminController::class, 'storeNotification']);
        Route::delete('/notifikasi/{notifikasi}', [AdminController::class, 'destroyNotification']);
        Route::post('/konten', [AdminController::class, 'storeContent']);
        Route::post('/konten/{konten}', [AdminController::class, 'updateContent']);
        Route::delete('/konten/{konten}', [AdminController::class, 'destroyContent']);
        Route::put('/pengaturan', [AdminController::class, 'updateSettings']);
        Route::put('/password', [AdminController::class, 'updatePassword']);
        Route::delete('/data/produk-pupuk', [AdminController::class, 'clearFertilizers']);
        Route::delete('/data/pesanan-pupuk', [AdminController::class, 'clearFertilizerOrders']);
        Route::delete('/data/admin', [AdminController::class, 'clearAdminData']);
    });
});

Route::middleware(['auth', 'role:petani', 'throttle:api'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/lahan-saya', function () {
        return view('lahan-saya', [
            'lahan' => LahanPetani::where('id_petani', auth()->id())->where('status', 'aktif')->get(),
        ]);
    })->name('lahan-saya');
    Route::view('/jadwal-tanam', 'jadwal-tanam')->name('jadwal-tanam');
    Route::view('/cuaca', 'cuaca')->name('cuaca');
    Route::view('/pupuk', 'pupuk')->name('pupuk');
    Route::view('/marketplace', 'marketplace')->name('marketplace');
    Route::view('/notifikasi', 'notifikasi')->name('notifikasi');
    Route::view('/lumbung-padi', 'lumbung-padi')->name('lumbung-padi');
    Route::view('/edukasi', 'edukasi')->name('edukasi');
    Route::view('/hama-penyakit', 'hama-penyakit')->name('hama-penyakit');
    Route::view('/profile', 'profile')->name('profile');
    Route::view('/data-diri', 'data-diri')->name('data-diri');
    Route::view('/riwayat-transaksi', 'riwayat-transaksi')->name('riwayat-transaksi');

    Route::get('/api/marketplace', [ProdukMarketplaceController::class, 'index']);
    Route::post('/api/marketplace', [ProdukMarketplaceController::class, 'store']);
    Route::post('/api/marketplace/{produkMarketplace}', [ProdukMarketplaceController::class, 'update']);
    Route::delete('/api/marketplace/{produkMarketplace}', [ProdukMarketplaceController::class, 'destroy']);
    Route::get('/api/marketplace-pesanan', [ProdukMarketplaceController::class, 'orders']);
    Route::patch('/api/marketplace-pesanan/{pesananMarketplace}', [ProdukMarketplaceController::class, 'updateOrder']);
    Route::get('/api/pupuk', [PupukController::class, 'index']);
    Route::post('/api/pupuk/pesanan', [PupukController::class, 'store']);
    Route::patch('/api/pupuk/pesanan/{pesananPupuk}/batalkan', [PupukController::class, 'cancelOrder']);
    Route::get('/api/jadwal-tanam', [JadwalTanamController::class, 'show']);
    Route::put('/api/jadwal-tanam/tanggal', [JadwalTanamController::class, 'updateSeedDate']);
    Route::post('/api/jadwal-tanam/mulai', [JadwalTanamController::class, 'start']);
    Route::post('/api/jadwal-tanam/tahap/{tahap}/selesai', [JadwalTanamController::class, 'completeStage']);
    Route::post('/api/jadwal-tanam/reset', [JadwalTanamController::class, 'reset']);
    Route::get('/api/hasil-panen', [HasilPanenController::class, 'index']);
    Route::post('/api/hasil-panen', [HasilPanenController::class, 'store']);
    Route::put('/api/hasil-panen/{hasilPanen}', [HasilPanenController::class, 'update']);
    Route::delete('/api/hasil-panen/{hasilPanen}', [HasilPanenController::class, 'destroy']);
});

Route::middleware(['auth', 'role:pembeli', 'throttle:api'])->group(function () {
    Route::get('/pembeli', fn () => redirect()->route('pembeli.marketplace'))->name('pembeli');
    Route::view('/pembeli/marketplace', 'pembeli.marketplace')->name('pembeli.marketplace');
    Route::view('/pembeli/notifikasi', 'pembeli.notifikasi')->name('pembeli.notifikasi');
    Route::view('/pembeli/pesanan', 'pembeli.riwayat-belanja')->name('pembeli.pesanan');
    Route::view('/pembeli/profile', 'pembeli.profile')->name('pembeli.profile');
    Route::view('/pembeli/data-diri', 'pembeli.data-diri')->name('pembeli.data-diri');
    Route::view('/pembeli/riwayat-belanja', 'pembeli.riwayat-belanja')->name('pembeli.riwayat-belanja');

    Route::get('/api/pembeli/marketplace', [ProdukMarketplaceController::class, 'index']);
    Route::get('/api/pembeli/pesanan', [ProdukMarketplaceController::class, 'orders']);
    Route::post('/api/pembeli/pesanan', [ProdukMarketplaceController::class, 'storeOrder']);
    Route::patch('/api/pembeli/pesanan/{pesananMarketplace}/batalkan', [ProdukMarketplaceController::class, 'cancelOrderByBuyer']);
});

Route::middleware(['auth', 'throttle:api'])->group(function () {
    Route::get('/api/notifikasi', [NotifikasiController::class, 'index']);
    Route::post('/api/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead']);
    Route::post('/api/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'markAsRead']);
    Route::get('/api/pengaturan', [PengaturanController::class, 'show']);
    Route::get('/api/konten', [KontenAplikasiController::class, 'index']);
    Route::get('/api/profile', [ProfileController::class, 'show']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/profile/foto', [ProfileController::class, 'updatePhoto']);
    Route::put('/api/profile/lokasi', [ProfileController::class, 'updateLocation']);
    Route::put('/api/profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/api/cuaca-lokasi', function (Request $request) {
    $lat = $request->query('lat');
    $lng = $request->query('lng');

    if (! is_numeric($lat) || ! is_numeric($lng)) {
        return response()->json(['message' => 'Koordinat lokasi tidak valid.'], 422);
    }

    try {
        $data = Cache::remember(
            'cuaca-lokasi-'.round((float) $lat, 3).'-'.round((float) $lng, 3),
            now()->addMinutes(15),
            function () use ($lat, $lng) {
                return Http::timeout((int) config('services.weather.timeout', 8))
                    ->retry(2, 200)
                    ->acceptJson()
                    ->get(config('services.weather.endpoint'), [
                        'lat' => $lat,
                        'lon' => $lng,
                    ])
                    ->throw()
                    ->json();
            }
        );

        $forecast = collect(data_get($data, 'data.forecast', []))
            ->filter(fn ($item) => is_array($item))
            ->values();
        $sekarang = now();
        $cuaca = $forecast->first(function ($item) use ($sekarang) {
            $waktuLokal = data_get($item, 'local_datetime');

            if (! $waktuLokal) {
                return false;
            }

            try {
                return Carbon::parse($waktuLokal)->greaterThanOrEqualTo($sekarang);
            } catch (Throwable) {
                return false;
            }
        }) ?? $forecast->first();

        if (! $cuaca) {
            return response()->json(['message' => 'Data cuaca lokasi belum tersedia.'], 404);
        }

        $terjemahanCuaca = [
            'Clear' => 'Cerah',
            'Sunny' => 'Cerah',
            'Partly Cloudy' => 'Cerah Berawan',
            'Mostly Cloudy' => 'Cerah Berawan',
            'Cloudy' => 'Berawan',
            'Overcast' => 'Berawan Tebal',
            'Haze' => 'Berkabut',
            'Mist' => 'Berkabut',
            'Fog' => 'Berkabut',
            'Light Rain' => 'Hujan Ringan',
            'Rain' => 'Hujan',
            'Heavy Rain' => 'Hujan Lebat',
            'Thunderstorm' => 'Hujan Petir',
        ];
        $namaIkonBmkg = [
            'Cerah' => 'cerah',
            'Cerah Berawan' => 'cerah berawan',
            'Berawan' => 'berawan',
            'Berawan Tebal' => 'berawan',
            'Berkabut' => 'kabut',
            'Hujan Ringan' => 'hujan ringan',
            'Hujan' => 'hujan sedang',
            'Hujan Lebat' => 'hujan lebat',
            'Hujan Petir' => 'hujan petir',
        ];
        $hariPanjang = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hariPendek = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $bulanPendek = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulanPanjang = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $terjemahkanCuaca = fn ($deskripsi) => $terjemahanCuaca[$deskripsi] ?? $deskripsi ?? 'Cuaca tidak tersedia';
        $buatIkonBmkg = function ($deskripsi, $waktuLokal = null) use ($namaIkonBmkg) {
            try {
                $jamLokal = (int) Carbon::parse($waktuLokal)->format('G');
            } catch (Throwable) {
                $jamLokal = (int) now()->format('G');
            }

            $periodeIkon = $jamLokal >= 18 || $jamLokal < 6 ? 'pm' : 'am';
            $fileIkon = ($namaIkonBmkg[$deskripsi] ?? 'cerah berawan')."-{$periodeIkon}.svg";

            return 'https://api-apps.bmkg.go.id/storage/icon/cuaca/'.str_replace('%2F', '/', rawurlencode($fileIkon));
        };
        $formatTanggalPanjang = function ($waktuLokal) use ($hariPanjang, $bulanPanjang) {
            $tanggal = Carbon::parse($waktuLokal);

            return $hariPanjang[$tanggal->dayOfWeek].', '.$tanggal->day.' '.$bulanPanjang[$tanggal->month].' '.$tanggal->year;
        };
        $formatTanggalPendek = function ($waktuLokal) use ($hariPendek, $bulanPendek) {
            $tanggal = Carbon::parse($waktuLokal);

            return [
                'hari' => $hariPendek[$tanggal->dayOfWeek],
                'tanggal' => $tanggal->day.' '.$bulanPendek[$tanggal->month],
                'tanggal_lengkap' => $tanggal->toDateString(),
            ];
        };
        $hitungPeluangHujan = function ($deskripsi) {
            $teks = mb_strtolower((string) $deskripsi);

            return match (true) {
                str_contains($teks, 'petir') => 90,
                str_contains($teks, 'lebat') => 85,
                str_contains($teks, 'hujan') => 70,
                str_contains($teks, 'gerimis') => 55,
                str_contains($teks, 'berawan') => 25,
                str_contains($teks, 'cerah') => 10,
                default => null,
            };
        };

        $deskripsiIndonesia = $terjemahkanCuaca(data_get($cuaca, 'weather'));
        $urlIkon = $buatIkonBmkg($deskripsiIndonesia, data_get($cuaca, 'local_datetime'));

        $prakiraanHarian = $forecast
            ->groupBy(fn ($item) => Carbon::parse(data_get($item, 'local_datetime'))->toDateString())
            ->take(5)
            ->map(function ($items) use ($terjemahkanCuaca, $buatIkonBmkg, $formatTanggalPendek) {
                $items = $items->values();
                $waktuPertama = data_get($items->first(), 'local_datetime');
                $tanggal = $formatTanggalPendek($waktuPertama);
                $suhu = $items
                    ->pluck('temperature')
                    ->filter(fn ($nilai) => is_numeric($nilai))
                    ->map(fn ($nilai) => (float) $nilai);
                $cuacaSiang = $items
                    ->sortBy(function ($item) {
                        try {
                            return abs((int) Carbon::parse(data_get($item, 'local_datetime'))->format('G') - 13);
                        } catch (Throwable) {
                            return 99;
                        }
                    })
                    ->first() ?? $items->first();
                $deskripsi = $terjemahkanCuaca(data_get($cuacaSiang, 'weather'));

                return [
                    'hari' => $tanggal['hari'],
                    'tanggal' => $tanggal['tanggal'],
                    'tanggal_lengkap' => $tanggal['tanggal_lengkap'],
                    'suhu_maks' => $suhu->isNotEmpty() ? round($suhu->max()) : null,
                    'suhu_min' => $suhu->isNotEmpty() ? round($suhu->min()) : null,
                    'deskripsi' => $deskripsi,
                    'ikon' => $buatIkonBmkg($deskripsi, data_get($cuacaSiang, 'local_datetime')),
                ];
            })
            ->values();

        return response()->json([
            'lokasi' => [
                'nama' => data_get($data, 'data.location', 'Lokasi Anda'),
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ],
            'cuaca' => [
                'suhu' => data_get($cuaca, 'temperature'),
                'kelembaban' => data_get($cuaca, 'humidity'),
                'angin' => data_get($cuaca, 'wind_speed'),
                'arah_angin' => data_get($cuaca, 'wind_direction'),
                'deskripsi' => $deskripsiIndonesia,
                'ikon' => $urlIkon,
                'peluang_hujan' => $hitungPeluangHujan($deskripsiIndonesia),
                'tanggal' => $formatTanggalPanjang(data_get($cuaca, 'local_datetime')),
                'waktu_lokal' => data_get($cuaca, 'local_datetime'),
            ],
            'prakiraan' => $prakiraanHarian,
            'sumber' => 'BMKG',
        ]);
    } catch (Throwable) {
        return response()->json(['message' => 'Gagal mengambil data cuaca lokasi.'], 502);
    }
})->middleware(['auth', 'throttle:weather']);
