<?php

namespace Tests\Feature;

use App\Models\BatasPupukPetani;
use App\Models\KontenAplikasi;
use App\Models\LahanPetani;
use App\Models\NotifikasiAplikasi;
use App\Models\PenerimaNotifikasi;
use App\Models\PesananPupuk;
use App\Models\ProdukMarketplace;
use App\Models\ProdukPupuk;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PoktanDatabaseFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_registration_pages_show_registration_terms(): void
    {
        $this->get('/daftar')
            ->assertOk()
            ->assertSee('Ketentuan Daftar Petani')
            ->assertSee('Akun petani menunggu aktivasi admin sebelum bisa digunakan.');

        $this->get('/daftar-pembeli')
            ->assertOk()
            ->assertSee('Ketentuan Daftar Pembeli')
            ->assertSee('Akun pembeli langsung aktif setelah pendaftaran berhasil.');
    }

    public function test_petani_registration_is_stored_in_database(): void
    {
        $this->post('/daftar', [
            'nik' => '1401010101010002',
            'nama' => 'Petani Baru',
            'no_hp' => '081200000002',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'nik' => '1401010101010002',
            'peran' => 'petani',
            'status' => 'menunggu',
        ]);
        $user = User::where('nik', '1401010101010002')->firstOrFail();
        $this->assertDatabaseHas('profil_petani', ['id_pengguna' => $user->id]);
    }

    public function test_buyer_registration_is_immediately_active(): void
    {
        $this->post('/daftar-pembeli', [
            'nama_lengkap' => 'Pembeli Baru',
            'no_handphone' => '081200000009',
            'nama_gudang' => 'Gudang Pembeli Baru',
            'password' => 'pembeli123',
            'password_confirmation' => 'pembeli123',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Pendaftaran pembeli berhasil. Akun sudah aktif dan dapat digunakan untuk login.');

        $this->assertDatabaseHas('users', [
            'nomor_hp' => '081200000009',
            'peran' => 'pembeli',
            'status' => 'aktif',
        ]);

        $buyer = User::where('nomor_hp', '081200000009')->firstOrFail();
        $this->assertDatabaseHas('profil_pembeli', [
            'id_pengguna' => $buyer->id,
            'nama_gudang' => 'Gudang Pembeli Baru',
        ]);

        $this->post('/login', [
            'username' => '081200000009',
            'password' => 'pembeli123',
        ])->assertRedirect(route('pembeli.marketplace'));
        $this->assertAuthenticatedAs($buyer);
    }

    public function test_registration_normalizes_indonesian_phone_numbers(): void
    {
        $this->post('/daftar', [
            'nik' => '1401010101010011',
            'nama' => 'Petani Format HP',
            'no_hp' => '+62 812-0000-0011',
            'password' => 'petani123',
            'password_confirmation' => 'petani123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'nik' => '1401010101010011',
            'nomor_hp' => '081200000011',
            'peran' => 'petani',
        ]);

        $this->post('/daftar-pembeli', [
            'nama_lengkap' => 'Pembeli Format HP',
            'no_handphone' => '62-812-0000-0012',
            'password' => 'pembeli123',
            'password_confirmation' => 'pembeli123',
        ])->assertRedirect(route('login'));

        $buyer = User::where('nomor_hp', '081200000012')->firstOrFail();

        $this->post('/login', [
            'username' => '+62 812 0000 0012',
            'password' => 'pembeli123',
        ])->assertRedirect(route('pembeli.marketplace'));

        $this->assertAuthenticatedAs($buyer);
    }

    public function test_buyer_registration_accepts_phone_without_leading_zero(): void
    {
        $this->post('/daftar-pembeli', [
            'nama_lengkap' => 'Pembeli Tanpa Nol',
            'no_handphone' => '81200000013',
            'password' => 'pembeli123',
            'password_confirmation' => 'pembeli123',
        ])->assertRedirect(route('login'));

        $buyer = User::where('nomor_hp', '081200000013')->firstOrFail();

        $this->post('/login', [
            'username' => '81200000013',
            'password' => 'pembeli123',
        ])->assertRedirect(route('pembeli.marketplace'));

        $this->assertAuthenticatedAs($buyer);
    }

    public function test_registration_validation_messages_are_in_indonesian(): void
    {
        $this->from('/daftar')->post('/daftar', [
            'nik' => '123',
            'nama' => '',
            'no_hp' => '',
            'password' => 'rahasia123',
            'password_confirmation' => 'berbeda123',
        ])
            ->assertRedirect('/daftar')
            ->assertSessionHasErrors([
                'nik' => 'NIK harus terdiri dari 16 digit angka.',
                'nama' => 'Nama lengkap wajib diisi.',
                'no_hp' => 'Nomor handphone wajib diisi.',
                'password' => 'Konfirmasi password tidak sesuai.',
            ]);

        $this->from('/daftar-pembeli')->post('/daftar-pembeli', [
            'nama_lengkap' => '',
            'no_handphone' => '',
            'nama_gudang' => str_repeat('a', 151),
            'password' => 'pembeli123',
            'password_confirmation' => 'berbeda123',
        ])
            ->assertRedirect('/daftar-pembeli')
            ->assertSessionHasErrors([
                'nama_lengkap' => 'Nama lengkap wajib diisi.',
                'no_handphone' => 'Nomor handphone wajib diisi.',
                'nama_gudang' => 'Nama gudang maksimal 150 karakter.',
                'password' => 'Konfirmasi password tidak sesuai.',
            ]);
    }

    public function test_registration_errors_are_displayed_in_the_related_fields(): void
    {
        $this->followingRedirects()
            ->from('/daftar')
            ->post('/daftar', [
                'nik' => '123',
                'nama' => '',
                'no_hp' => '',
                'password' => 'rahasia123',
                'password_confirmation' => 'berbeda123',
            ])
            ->assertOk()
            ->assertSee('data-registration-alert', false)
            ->assertSee('id="nik-error"', false)
            ->assertSee('id="password-error"', false)
            ->assertSee('NIK harus terdiri dari 16 digit angka.');

        $this->followingRedirects()
            ->from('/daftar-pembeli')
            ->post('/daftar-pembeli', [
                'nama_lengkap' => '',
                'no_handphone' => '',
                'nama_gudang' => str_repeat('G', 151),
                'password' => 'pembeli123',
                'password_confirmation' => 'berbeda123',
            ])
            ->assertOk()
            ->assertSee('data-registration-alert', false)
            ->assertSee('id="nama_lengkap-error"', false)
            ->assertSee('id="nama_gudang-error"', false)
            ->assertSee('Nama gudang maksimal 150 karakter.');
    }

    public function test_admin_cannot_make_a_buyer_account_inactive(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->postJson('/api/admin/pengguna', [
            'name' => 'Pembeli Aktif Otomatis',
            'phone' => '081200000010',
            'role' => 'Pembeli',
            'warehouseName' => 'Gudang Otomatis',
            'address' => 'Lancang Kuning',
            'status' => 'Nonaktif',
            'password' => 'pembeli123',
            'password_confirmation' => 'pembeli123',
        ])->assertCreated();

        $this->assertDatabaseHas('users', [
            'id' => $response->json('id'),
            'peran' => 'pembeli',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)->putJson('/api/admin/pengguna/'.$response->json('id'), [
            'name' => 'Pembeli Aktif Otomatis',
            'phone' => '081200000010',
            'role' => 'Pembeli',
            'warehouseName' => 'Gudang Otomatis',
            'address' => 'Lancang Kuning',
            'status' => 'Nonaktif',
        ])->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $response->json('id'),
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_create_active_farmer(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)->postJson('/api/admin/pengguna', [
            'name' => 'Petani API',
            'phone' => '081200000003',
            'role' => 'Petani',
            'nik' => '1401010101010003',
            'address' => 'Lancang Kuning',
            'landAreaMeter' => 1500,
            'fertilizerLimits' => [],
            'status' => 'Aktif',
            'password' => 'petani123',
            'password_confirmation' => 'petani123',
        ])->assertCreated();

        $this->assertDatabaseHas('users', ['nik' => '1401010101010003', 'status' => 'aktif']);
        $user = User::where('nik', '1401010101010003')->firstOrFail();
        $this->assertDatabaseHas('lahan_petani', [
            'id_petani' => $user->id,
            'luas_meter' => 1500,
        ]);
    }

    public function test_fertilizer_purchase_limit_must_be_a_whole_number(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $fertilizer = ProdukPupuk::firstOrFail();

        $this->actingAs($admin)->postJson('/api/admin/pengguna', [
            'name' => 'Petani Batas Desimal',
            'phone' => '081200000099',
            'role' => 'Petani',
            'nik' => '1401010101010099',
            'address' => 'Lancang Kuning',
            'landAreaMeter' => 1000,
            'fertilizerLimits' => [$fertilizer->id => 2.5],
            'status' => 'Aktif',
            'password' => 'petani123',
            'password_confirmation' => 'petani123',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors("fertilizerLimits.{$fertilizer->id}");
    }

    public function test_marketplace_stock_and_order_quantities_must_be_whole_numbers(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);

        $this->actingAs($farmer)->postJson('/api/marketplace', [
            'nama_produk' => 'Gabah Stok Pecahan',
            'harga' => 10000,
            'jumlah_stok' => 20.5,
            'satuan' => 'kg',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('jumlah_stok');

        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Gabah Pesanan Pecahan',
            'harga' => 10000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'aktif' => true,
        ]);

        $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 1.5,
            'metode_pembayaran' => 'tunai',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('jumlah');
    }

    public function test_marketplace_order_is_saved_and_can_be_approved(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Gabah Kering',
            'harga' => 10000,
            'jumlah_stok' => 50,
            'satuan' => 'kg',
            'aktif' => true,
        ]);

        $this->actingAs($buyer)->get('/pembeli/marketplace')
            ->assertOk()
            ->assertSee('data-catatan-pembeli', false);

        $response = $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 5,
            'metode_pembayaran' => 'tunai',
            'catatan' => 'Mohon dikemas rapi dan hubungi sebelum pengiriman.',
        ])->assertCreated();

        $orderId = $response->json('id');
        $this->assertDatabaseHas('detail_pesanan_marketplace', [
            'id_pesanan_marketplace' => $orderId,
            'jumlah' => 5,
        ]);
        $this->assertDatabaseHas('pesanan_marketplace', [
            'id' => $orderId,
            'catatan_pembeli' => 'Mohon dikemas rapi dan hubungi sebelum pengiriman.',
        ]);

        $this->actingAs($farmer)->getJson('/api/marketplace-pesanan')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $orderId,
                'catatan' => 'Mohon dikemas rapi dan hubungi sebelum pengiriman.',
            ]);

        $this->actingAs($farmer)->patchJson("/api/marketplace-pesanan/{$orderId}", [
            'status' => 'disetujui',
        ])->assertOk();

        $this->assertDatabaseHas('produk_marketplace', ['id' => $product->id, 'jumlah_stok' => 45]);
    }

    public function test_buyer_can_cancel_a_pending_marketplace_order(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Beras Putih',
            'harga' => 15000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'aktif' => true,
        ]);

        $orderId = $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 3,
            'metode_pembayaran' => 'tunai',
        ])->assertCreated()->json('id');

        $this->actingAs($buyer)->getJson('/api/notifikasi')
            ->assertOk()
            ->assertJsonFragment([
                'orderId' => $orderId,
                'status' => 'menunggu',
                'judul' => 'Pesanan menunggu persetujuan',
            ]);

        $this->actingAs($buyer)
            ->patchJson("/api/pembeli/pesanan/{$orderId}/batalkan")
            ->assertOk()
            ->assertJsonPath('message', 'Pesanan berhasil dibatalkan.');

        $this->assertDatabaseHas('pesanan_marketplace', [
            'id' => $orderId,
            'status_pesanan' => 'dibatalkan',
            'status_pembayaran' => 'dibatalkan',
        ]);
        $this->actingAs($farmer)->patchJson("/api/marketplace-pesanan/{$orderId}", [
            'status' => 'disetujui',
        ])->assertUnprocessable();
    }

    public function test_farmer_can_create_marketplace_product_with_an_original_photo_up_to_two_megabytes(): void
    {
        Storage::fake('public');
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $this->actingAs($farmer)
            ->get('/marketplace')
            ->assertOk()
            ->assertSee('data-form-produk-status', false)
            ->assertSee('accept="image/jpeg,image/png,image/webp"', false)
            ->assertSee('data-input-harga-produk', false)
            ->assertSee('Harga otomatis ditulis dalam format Rupiah.');

        $response = $this->actingAs($farmer)->post('/api/marketplace', [
            'nama_produk' => 'Gabah Wangi',
            'deskripsi' => 'Gabah hasil panen terbaru.',
            'alamat_produk' => 'Lancang Kuning',
            'harga' => 12500,
            'jumlah_stok' => 75,
            'satuan' => 'kg',
            'gambar' => UploadedFile::fake()->image('gabah.png', 1200, 900)->size(2048),
        ], ['Accept' => 'application/json'])->assertCreated();

        $storedPath = str_replace('/storage/', '', (string) $response->json('gambar'));
        Storage::disk('public')->assertExists($storedPath);
        $this->assertDatabaseHas('produk_marketplace', [
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Gabah Wangi',
        ]);
    }

    public function test_marketplace_photo_validation_returns_a_clear_message(): void
    {
        Storage::fake('public');
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $this->actingAs($farmer)->post('/api/marketplace', [
            'nama_produk' => 'Gabah Besar',
            'deskripsi' => 'Foto melebihi batas.',
            'alamat_produk' => 'Lancang Kuning',
            'harga' => 10000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'gambar' => UploadedFile::fake()->image('gabah.png', 1200, 900)->size(2049),
        ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.gambar.0', 'Ukuran foto maksimal 2 MB.');
    }

    public function test_schedule_and_harvest_are_separated_per_farmer(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $schedule = $this->actingAs($farmer)->getJson('/api/jadwal-tanam')->assertOk();
        $stageId = $schedule->json('tahapan.0.id');

        $this->actingAs($farmer)
            ->putJson('/api/jadwal-tanam/tanggal', [
                'tanggal_semai' => today()->subDays(10)->format('Y-m-d'),
            ])
            ->assertOk()
            ->assertJsonPath('status', 'rencana');

        $this->actingAs($farmer)
            ->postJson('/api/jadwal-tanam/mulai')
            ->assertOk()
            ->assertJsonPath('status', 'aktif')
            ->assertJsonPath('tanggalSemai', today()->format('Y-m-d'));

        $this->actingAs($farmer)
            ->postJson("/api/jadwal-tanam/tahap/{$stageId}/selesai", [
                'konfirmasi_peringatan' => true,
            ])
            ->assertOk()
            ->assertJsonPath('jumlahSelesai', 1);

        $this->actingAs($farmer)->postJson('/api/hasil-panen', [
            'jumlah' => 1250.5,
            'jenis_bibit' => 'Ciherang',
        ])->assertCreated();

        $this->assertDatabaseHas('hasil_panen_padi', [
            'id_petani' => $farmer->id,
            'jenis_bibit' => 'Ciherang',
        ]);

        $this->actingAs($farmer)
            ->get('/lumbung-padi')
            ->assertOk()
            ->assertSee('style="--gambar-lumbung:', false)
            ->assertSee('assets/orang_panen.png', false);
    }

    public function test_farmer_can_update_and_delete_only_their_own_harvest(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $otherFarmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $harvest = $this->actingAs($farmer)->postJson('/api/hasil-panen', [
            'jumlah' => 800,
            'jenis_bibit' => 'Inpari 32',
            'tanggal_panen' => today()->subDay()->format('Y-m-d'),
        ])->assertCreated();
        $harvestId = $harvest->json('id');

        $this->actingAs($farmer)->putJson("/api/hasil-panen/{$harvestId}", [
            'jumlah' => 950.5,
            'jenis_bibit' => 'Inpari 42',
            'tanggal_panen' => today()->format('Y-m-d'),
        ])
            ->assertOk()
            ->assertJsonPath('jumlah', 950.5)
            ->assertJsonPath('jenisBibit', 'Inpari 42');

        $this->assertDatabaseHas('hasil_panen_padi', [
            'id' => $harvestId,
            'id_petani' => $farmer->id,
            'jumlah_kg' => 950.5,
            'jenis_bibit' => 'Inpari 42',
        ]);

        $this->actingAs($otherFarmer)->putJson("/api/hasil-panen/{$harvestId}", [
            'jumlah' => 1,
            'jenis_bibit' => 'Bukan Milik Saya',
            'tanggal_panen' => today()->format('Y-m-d'),
        ])->assertNotFound();

        $this->actingAs($otherFarmer)
            ->deleteJson("/api/hasil-panen/{$harvestId}")
            ->assertNotFound();

        $this->actingAs($farmer)
            ->deleteJson("/api/hasil-panen/{$harvestId}")
            ->assertOk()
            ->assertJsonPath('message', 'Hasil panen berhasil dihapus.');

        $this->assertSoftDeleted('hasil_panen_padi', ['id' => $harvestId]);
    }

    public function test_seedling_and_planting_stages_warn_before_ten_days(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $this->actingAs($farmer)
            ->get('/jadwal-tanam')
            ->assertOk()
            ->assertSee('assets/tanam_padi.png', false)
            ->assertSee('data-mulai-jadwal', false)
            ->assertSee('Mulai Proses Tanam')
            ->assertSee('Tanggal mulai otomatis mengikuti hari saat proses tanam dimulai.')
            ->assertDontSee('assets/edukasi/tanam_padi.png', false);

        $schedule = $this->actingAs($farmer)
            ->getJson('/api/jadwal-tanam')
            ->assertOk()
            ->assertJsonPath('status', 'rencana')
            ->assertJsonPath('tahapan.0.status', 'menunggu')
            ->assertJsonPath('tahapan.0.kurangMinimum', false);

        $schedule = $this->actingAs($farmer)
            ->postJson('/api/jadwal-tanam/mulai')
            ->assertOk()
            ->assertJsonPath('status', 'aktif')
            ->assertJsonPath('tanggalSemai', today()->format('Y-m-d'))
            ->assertJsonPath('tahapan.0.mulaiAktual', today()->format('Y-m-d'))
            ->assertJsonPath('tahapan.0.minimumHari', 10)
            ->assertJsonPath('tahapan.0.hariBerjalan', 0)
            ->assertJsonPath('tahapan.0.sisaHari', 10)
            ->assertJsonPath('tahapan.0.kurangMinimum', true)
            ->assertJsonPath('tahapan.0.dapatDiselesaikan', false);
        $seedlingId = $schedule->json('tahapan.0.id');

        $this->actingAs($farmer)
            ->postJson("/api/jadwal-tanam/tahap/{$seedlingId}/selesai")
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Tahap Pembibitan baru berjalan 0 hari. Konfirmasi diperlukan untuk menyelesaikannya sebelum minimal 10 hari.');

        $this->actingAs($farmer)
            ->postJson("/api/jadwal-tanam/tahap/{$seedlingId}/selesai", [
                'konfirmasi_peringatan' => true,
            ])
            ->assertOk()
            ->assertJsonPath('tahapan.1.minimumHari', 10)
            ->assertJsonPath('tahapan.1.hariBerjalan', 0)
            ->assertJsonPath('tahapan.1.sisaHari', 10)
            ->assertJsonPath('tahapan.1.kurangMinimum', true)
            ->assertJsonPath('tahapan.1.dapatDiselesaikan', false);
    }

    public function test_marketplace_settings_are_enforced_by_the_server(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Beras',
            'harga' => 15000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'aktif' => true,
        ]);
        DB::table('pengaturan_aplikasi')->where('id', 1)->update(['status_marketplace' => 'nonaktif']);

        $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 2,
            'metode_pembayaran' => 'tunai',
        ])->assertUnprocessable()->assertJsonPath('message', 'Marketplace pembeli sedang tidak aktif.');
    }

    public function test_farmer_can_select_active_fertilizer_payment_methods_at_checkout(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $product = ProdukPupuk::firstOrFail();

        $this->actingAs($farmer)
            ->get('/pupuk')
            ->assertOk()
            ->assertSee('data-payment-option', false)
            ->assertSee('id="pupuk-pembayaran-transfer"', false)
            ->assertSee('id="pupuk-pembayaran-qris"', false)
            ->assertSee('id="pupuk-pembayaran-tunai"', false)
            ->assertSee('data-submit-checkout', false)
            ->assertSee('Pantau rincian dan status pesanan pupuk Anda.')
            ->assertSee('data-daftar-riwayat aria-live="polite"', false);

        $this->actingAs($farmer)
            ->getJson('/api/pupuk')
            ->assertOk()
            ->assertJsonPath('paymentMethods.Transfer', true)
            ->assertJsonPath('paymentMethods.QRIS', true)
            ->assertJsonPath('paymentMethods.Tunai', true);

        foreach (['transfer', 'qris', 'tunai'] as $method) {
            $this->actingAs($farmer)->postJson('/api/pupuk/pesanan', [
                'metode_pembayaran' => $method,
                'items' => [
                    ['id' => $product->id, 'jumlah' => 1],
                ],
            ])->assertCreated();
        }

        $this->assertDatabaseCount('pesanan_pupuk', 3);

        $this->actingAs($farmer)
            ->getJson('/api/pupuk')
            ->assertOk()
            ->assertJsonCount(3, 'orders')
            ->assertJsonStructure([
                'orders' => [[
                    'tanggal',
                    'metode',
                    'status',
                    'total',
                    'items' => [['nama', 'jumlah', 'satuan', 'harga']],
                ]],
            ]);
    }

    public function test_fertilizer_purchase_reduces_limit_and_blocks_product_when_exhausted(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $product = ProdukPupuk::firstOrFail();

        BatasPupukPetani::create([
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 2,
            'aktif' => true,
        ]);

        $initialProducts = $this->actingAs($farmer)
            ->getJson('/api/pupuk')
            ->assertOk()
            ->json('products');
        $initialProduct = collect($initialProducts)->firstWhere('id', $product->id);
        $this->assertTrue($initialProduct['dibatasi']);
        $this->assertSame(2, $initialProduct['batas']);

        $this->actingAs($farmer)->postJson('/api/pupuk/pesanan', [
            'metode_pembayaran' => 'tunai',
            'items' => [
                ['id' => $product->id, 'jumlah' => 2],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 0,
        ]);

        $exhaustedProducts = $this->actingAs($farmer)
            ->getJson('/api/pupuk')
            ->assertOk()
            ->json('products');
        $exhaustedProduct = collect($exhaustedProducts)->firstWhere('id', $product->id);
        $this->assertTrue($exhaustedProduct['dibatasi']);
        $this->assertSame(0, $exhaustedProduct['batas']);

        $this->actingAs($farmer)->postJson('/api/pupuk/pesanan', [
            'metode_pembayaran' => 'tunai',
            'items' => [
                ['id' => $product->id, 'jumlah' => 1],
            ],
        ])->assertUnprocessable()
            ->assertJsonPath('message', "Batas pembelian {$product->nama_produk} sudah habis.");
    }

    public function test_farmer_can_cancel_pending_fertilizer_order_and_restore_purchase_limit(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $otherFarmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $product = ProdukPupuk::firstOrFail();

        BatasPupukPetani::create([
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 4,
            'aktif' => true,
        ]);

        $this->actingAs($farmer)
            ->get('/pupuk')
            ->assertOk()
            ->assertSee('data-status-riwayat-pupuk', false);

        $orderId = $this->actingAs($farmer)->postJson('/api/pupuk/pesanan', [
            'metode_pembayaran' => 'tunai',
            'items' => [
                ['id' => $product->id, 'jumlah' => 3],
            ],
        ])->assertCreated()->json('id');

        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 1,
        ]);

        $this->actingAs($otherFarmer)
            ->patchJson("/api/pupuk/pesanan/{$orderId}/batalkan")
            ->assertForbidden()
            ->assertJsonPath('message', 'Pesanan pupuk ini bukan milik Anda.');

        $this->actingAs($farmer)
            ->patchJson("/api/pupuk/pesanan/{$orderId}/batalkan")
            ->assertOk()
            ->assertJsonPath('message', 'Pesanan pupuk berhasil dibatalkan.');

        $this->assertDatabaseHas('pesanan_pupuk', [
            'id' => $orderId,
            'id_petani' => $farmer->id,
            'status_pesanan' => 'dibatalkan',
            'status_pembayaran' => 'dibatalkan',
        ]);
        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 4,
        ]);

        $this->actingAs($farmer)
            ->getJson('/api/pupuk')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $orderId,
                'status' => 'dibatalkan',
                'bisaDibatalkan' => false,
            ]);

        $this->actingAs($farmer)
            ->patchJson("/api/pupuk/pesanan/{$orderId}/batalkan")
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Pesanan pupuk hanya dapat dibatalkan sebelum diproses admin.');
    }

    public function test_rejected_fertilizer_order_restores_farmer_purchase_limit_once(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $product = ProdukPupuk::firstOrFail();

        BatasPupukPetani::create([
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 3,
            'aktif' => true,
        ]);

        $orderId = $this->actingAs($farmer)->postJson('/api/pupuk/pesanan', [
            'metode_pembayaran' => 'tunai',
            'items' => [
                ['id' => $product->id, 'jumlah' => 2],
            ],
        ])->assertCreated()->json('id');

        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 1,
        ]);

        $this->actingAs($admin)
            ->patchJson("/api/admin/pesanan-pupuk/{$orderId}", ['status' => 'ditolak'])
            ->assertOk()
            ->assertJsonPath('message', 'Pesanan ditolak dan batas pembelian pupuk dikembalikan.');

        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 3,
        ]);

        $this->actingAs($admin)
            ->patchJson("/api/admin/pesanan-pupuk/{$orderId}", ['status' => 'ditolak'])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Perubahan status pesanan tidak valid.');

        $this->assertDatabaseHas('batas_pupuk_petani', [
            'id_petani' => $farmer->id,
            'id_produk_pupuk' => $product->id,
            'maksimal_jumlah' => 3,
        ]);
    }

    public function test_farmer_notifications_are_synchronized_without_transaction_duplicates(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $otherFarmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Gabah Sinkron',
            'harga' => 10000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'aktif' => true,
        ]);

        $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 2,
            'metode_pembayaran' => 'tunai',
        ])->assertCreated();

        $roleNotification = NotifikasiAplikasi::create([
            'kategori' => 'pupuk',
            'target_peran' => 'petani',
            'judul' => 'Pupuk tersedia',
            'pesan' => 'Stok pupuk sudah tersedia.',
            'diterbitkan_pada' => now(),
        ]);
        NotifikasiAplikasi::create([
            'kategori' => 'sistem',
            'target_peran' => 'pembeli',
            'judul' => 'Khusus pembeli',
            'pesan' => 'Tidak boleh tampil untuk petani.',
            'diterbitkan_pada' => now(),
        ]);
        NotifikasiAplikasi::create([
            'kategori' => 'cuaca',
            'target_peran' => 'petani',
            'judul' => 'Cuaca masa depan',
            'pesan' => 'Belum waktunya tampil.',
            'diterbitkan_pada' => now()->addDay(),
        ]);
        NotifikasiAplikasi::create([
            'kategori' => 'sistem',
            'target_peran' => 'petani',
            'judul' => 'Notifikasi kedaluwarsa',
            'pesan' => 'Sudah tidak berlaku.',
            'diterbitkan_pada' => now()->subDays(2),
            'berakhir_pada' => now()->subDay(),
        ]);

        $response = $this->actingAs($farmer)->getJson('/api/notifikasi')->assertOk();
        $items = collect($response->json('items'));

        $this->assertCount(1, $items->where('kategori', 'transaksi'));
        $this->assertSame('menunggu', $items->firstWhere('kategori', 'transaksi')['status']);
        $this->assertNotNull($items->firstWhere('judul', 'Pupuk tersedia'));
        $this->assertNull($items->firstWhere('judul', 'Khusus pembeli'));
        $this->assertNull($items->firstWhere('judul', 'Cuaca masa depan'));
        $this->assertNull($items->firstWhere('judul', 'Notifikasi kedaluwarsa'));
        $this->assertSame($items->count(), $response->json('unread'));

        $this->actingAs($farmer)
            ->postJson("/api/notifikasi/{$roleNotification->id}/baca")
            ->assertOk();
        $this->assertDatabaseHas('penerima_notifikasi', [
            'id_notifikasi' => $roleNotification->id,
            'id_pengguna' => $farmer->id,
        ]);
        $this->assertNotNull(PenerimaNotifikasi::where([
            'id_notifikasi' => $roleNotification->id,
            'id_pengguna' => $farmer->id,
        ])->value('dibaca_pada'));

        $this->actingAs($otherFarmer)
            ->postJson("/api/notifikasi/{$roleNotification->id}/baca")
            ->assertOk();

        $specificNotification = NotifikasiAplikasi::where('target_peran', 'khusus')
            ->where('data_tambahan->id_pengguna', $farmer->id)
            ->firstOrFail();
        $this->actingAs($otherFarmer)
            ->postJson("/api/notifikasi/{$specificNotification->id}/baca")
            ->assertNotFound();

        $this->actingAs($farmer)->postJson('/api/notifikasi/baca-semua')->assertOk();
        $this->actingAs($farmer)
            ->getJson('/api/notifikasi')
            ->assertOk()
            ->assertJsonPath('unread', 0);
    }

    public function test_seeder_does_not_overwrite_an_existing_admin_password(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $admin->update(['password' => 'password-yang-sudah-diganti']);

        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(Hash::check('password-yang-sudah-diganti', $admin->fresh()->password));
    }

    public function test_role_middleware_redirects_wrong_page_without_showing_403(): void
    {
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($buyer)->get('/dashboard')->assertRedirect(route('pembeli.marketplace'));
        $this->actingAs($farmer)->get('/admin')->assertRedirect(route('dashboard'));
        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('admin'));
    }

    public function test_wrong_role_api_returns_redirect_information_instead_of_403(): void
    {
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);

        $this->actingAs($buyer)
            ->getJson('/api/jadwal-tanam')
            ->assertStatus(409)
            ->assertJsonPath('redirect', route('pembeli.marketplace'));
    }

    public function test_authenticated_users_can_open_login_to_switch_accounts(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);

        $this->actingAs($admin)->get('/login')->assertOk()->assertSee('Masuk ke Akun Anda');
        $this->actingAs($farmer)
            ->get('/login')
            ->assertOk()
            ->assertDontSee('name="peran"', false);
        $this->actingAs($buyer)->get('/login')->assertOk()->assertSee('Masuk ke Akun Anda');
    }

    public function test_admin_session_can_login_again_as_farmer(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create([
            'username' => 'petani.switch',
            'nomor_hp' => '081211110001',
            'nik' => '1401010101011001',
            'peran' => 'petani',
            'status' => 'aktif',
            'password' => 'petani123',
        ]);

        $this->actingAs($admin)->post('/login', [
            'username' => $farmer->nomor_hp,
            'password' => 'petani123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($farmer);
    }

    public function test_login_detects_each_account_role_and_routes_to_its_home(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create([
            'username' => 'petani.login',
            'nomor_hp' => '081211110002',
            'nik' => '1401010101011002',
            'peran' => 'petani',
            'status' => 'aktif',
            'password' => 'password',
        ]);
        $buyer = User::factory()->create([
            'username' => 'pembeli.login',
            'nomor_hp' => '081211110003',
            'peran' => 'pembeli',
            'status' => 'aktif',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'username' => $admin->username,
            'password' => 'admin123',
        ])->assertRedirect(route('admin'));
        $this->assertAuthenticatedAs($admin);
        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'username' => $farmer->nik,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($farmer);
        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'username' => $buyer->nomor_hp,
            'password' => 'password',
        ])->assertRedirect(route('pembeli.marketplace'));
        $this->assertAuthenticatedAs($buyer);
    }

    public function test_farmer_can_login_with_nik_or_phone_and_buyer_only_with_phone(): void
    {
        $farmer = User::factory()->create([
            'name' => 'Petani Login Khusus',
            'username' => 'username.petani.tidak.dipakai',
            'nomor_hp' => '081211110004',
            'nik' => '1401010101011004',
            'peran' => 'petani',
            'status' => 'aktif',
            'password' => 'password',
        ]);
        $buyer = User::factory()->create([
            'name' => 'Pembeli Login Khusus',
            'username' => 'username.pembeli.tidak.dipakai',
            'nomor_hp' => '081211110005',
            'peran' => 'pembeli',
            'status' => 'aktif',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'username' => $farmer->nik,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($farmer);
        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'username' => $farmer->nomor_hp,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($farmer);
        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'username' => $buyer->nomor_hp,
            'password' => 'password',
        ])->assertRedirect(route('pembeli.marketplace'));
        $this->assertAuthenticatedAs($buyer);
        $this->post('/logout')->assertRedirect(route('login'));

        $this->from('/login')->post('/login', [
            'username' => $buyer->username,
            'password' => 'password',
        ])->assertRedirect('/login')->assertSessionHasErrors('username');
        $this->assertGuest();

        $this->from('/login')->post('/login', [
            'username' => $farmer->name,
            'password' => 'password',
        ])->assertRedirect('/login')->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_ignores_a_submitted_role_and_uses_the_database_role(): void
    {
        $farmer = User::factory()->create([
            'username' => 'petani.role',
            'nomor_hp' => '081211110006',
            'nik' => '1401010101011006',
            'peran' => 'petani',
            'status' => 'aktif',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'peran' => 'pembeli',
            'username' => $farmer->nik,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($farmer);
    }

    public function test_login_ignores_a_stale_intended_url_from_another_role(): void
    {
        $farmer = User::factory()->create([
            'username' => 'petani.intended',
            'nomor_hp' => '081211110007',
            'nik' => '1401010101011007',
            'peran' => 'petani',
            'status' => 'aktif',
            'password' => 'password',
        ]);

        $this->withSession(['url.intended' => route('admin')])->post('/login', [
            'username' => $farmer->nomor_hp,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($farmer);
        $this->assertNull(session('url.intended'));
    }

    public function test_admin_can_clear_operational_data_without_deleting_admin_account(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);

        $this->actingAs($admin)->deleteJson('/api/admin/data/admin', [
            'current_password' => 'admin123',
        ])->assertOk();

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'peran' => 'admin']);
        $this->assertDatabaseMissing('users', ['peran' => 'petani']);
        $this->assertDatabaseCount('produk_pupuk', 0);
        $this->assertDatabaseHas('pengaturan_aplikasi', ['id' => 1, 'status_marketplace' => 'aktif']);
    }

    public function test_bulk_admin_deletion_requires_the_current_password(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->deleteJson('/api/admin/data/produk-pupuk', ['current_password' => 'salah-password'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('current_password');

        $this->assertDatabaseCount('produk_pupuk', 4);
    }

    public function test_farmer_registration_creates_default_land(): void
    {
        $this->post('/daftar', [
            'nik' => '1401010101010004',
            'nama' => 'Petani Berlahan',
            'no_hp' => '081200000004',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('login'));

        $farmer = User::where('nik', '1401010101010004')->firstOrFail();

        $this->assertDatabaseHas('lahan_petani', [
            'id_petani' => $farmer->id,
            'nama_lahan' => 'Lahan Padi',
            'luas_meter' => 0,
        ]);

        $this->actingAs($farmer)
            ->get('/lahan-saya')
            ->assertOk()
            ->assertSee('style="--gambar-lahan:', false)
            ->assertSee('assets/lahan_sawah.png', false);
    }

    public function test_farmer_and_buyer_profiles_show_whatsapp_help_menu(): void
    {
        $farmer = User::factory()->create([
            'peran' => 'petani',
            'status' => 'aktif',
        ]);
        $buyer = User::factory()->create([
            'peran' => 'pembeli',
            'status' => 'aktif',
        ]);

        $this->actingAs($farmer)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('item-menu-whatsapp', false)
            ->assertSee('assets/profile/whatsApp.png', false)
            ->assertSee('https://wa.me/6282177119351', false);

        $this->actingAs($buyer)
            ->get(route('pembeli.profile'))
            ->assertOk()
            ->assertSee('item-menu-whatsapp', false)
            ->assertSee('assets/profile/whatsApp.png', false)
            ->assertSee('https://wa.me/6282177119351', false);
    }

    public function test_admin_dashboard_bootstrap_contains_complete_summary_and_activities(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        User::factory()->create([
            'name' => 'Petani Menunggu',
            'peran' => 'petani',
            'status' => 'menunggu',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertDontSee('href="'.route('dashboard').'"', false)
            ->assertSee('data-admin-refresh', false)
            ->assertSee('data-admin-settings-form', false)
            ->assertSee('data-admin-setting-app-name', false)
            ->assertSee('data-admin-setting-location', false)
            ->assertSee('data-admin-settings-feedback', false)
            ->assertSee('data-admin-content-success', false)
            ->assertSee('data-admin-user-search-form', false)
            ->assertSee('Cari nama atau NIK petani')
            ->assertSee('Cari nama atau No. HP pembeli')
            ->assertSee('Harga otomatis ditulis dalam format Rupiah.')
            ->assertDontSee('Area Data Database')
            ->assertDontSee('data-admin-clear-fertilizers', false)
            ->assertDontSee('data-admin-clear-orders', false)
            ->assertDontSee('data-admin-clear-all', false);

        $this->actingAs($admin)
            ->getJson('/api/admin/bootstrap')
            ->assertOk()
            ->assertJsonPath('stats.menunggu', 1)
            ->assertJsonStructure([
                'stats' => ['petani', 'pembeli', 'menunggu', 'produk', 'pupuk', 'pesanan'],
                'activities' => [['description', 'category', 'status', 'occurredAt']],
                'users',
                'fertilizers',
                'orders',
                'notifications',
                'contents',
                'settings',
                'planting',
            ]);
    }

    public function test_admin_can_open_the_rdkk_print_report_menu(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Cetak Laporan')
            ->assertSee('Rencana Definitif Kebutuhan Kelompok', false)
            ->assertSee('Rencana Tanam (Ha)', false)
            ->assertSee('Jumlah Pupuk Bersubsidi (Karung)', false)
            ->assertSeeInOrder(['submenu-sistem', '#tab-pengaturan', '#tab-akun-admin', '</details>', '#tab-laporan'], false)
            ->assertSee('data-admin-print-report', false)
            ->assertSee('data-rdkk-rows', false);
    }

    public function test_rdkk_report_uses_total_active_farmer_land_as_hectare(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        LahanPetani::create([
            'id_petani' => $farmer->id,
            'nama_lahan' => 'Lahan Utama',
            'luas_meter' => 1200,
            'status' => 'aktif',
        ]);
        LahanPetani::create([
            'id_petani' => $farmer->id,
            'nama_lahan' => 'Lahan Tambahan',
            'luas_meter' => 800,
            'status' => 'aktif',
        ]);
        LahanPetani::create([
            'id_petani' => $farmer->id,
            'nama_lahan' => 'Lahan Tidak Aktif',
            'luas_meter' => 500,
            'status' => 'nonaktif',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/admin/bootstrap')->assertOk();
        $reportFarmer = collect($response->json('users'))->firstWhere('id', $farmer->id);

        $this->assertSame(0.2, $reportFarmer['rencana_tanam_ha']);
    }

    public function test_rdkk_report_uses_farmer_fertilizer_purchase_data(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $otherFarmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $urea = ProdukPupuk::where('nama_produk', 'Urea')->firstOrFail();
        $npkFormula = ProdukPupuk::where('nama_produk', 'NPK 16-16-16')->firstOrFail();
        $year = (string) now()->year;

        $validOrder = PesananPupuk::create([
            'nomor_pesanan' => 'PPK-LAPORAN-001',
            'id_petani' => $farmer->id,
            'metode_pembayaran' => 'tunai',
            'status_pesanan' => 'diterima',
            'total_harga' => 0,
            'dipesan_pada' => now(),
        ]);
        DB::table('detail_pesanan_pupuk')->insert([
            [
                'id_pesanan_pupuk' => $validOrder->id,
                'id_produk_pupuk' => $urea->id,
                'nama_produk' => $urea->nama_produk,
                'jumlah' => 2,
                'satuan' => '50 kg',
                'harga_satuan' => 0,
                'subtotal' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pesanan_pupuk' => $validOrder->id,
                'id_produk_pupuk' => $npkFormula->id,
                'nama_produk' => $npkFormula->nama_produk,
                'jumlah' => 3,
                'satuan' => '50 kg',
                'harga_satuan' => 0,
                'subtotal' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $cancelledOrder = PesananPupuk::create([
            'nomor_pesanan' => 'PPK-LAPORAN-002',
            'id_petani' => $farmer->id,
            'metode_pembayaran' => 'tunai',
            'status_pesanan' => 'dibatalkan',
            'status_pembayaran' => 'dibatalkan',
            'total_harga' => 0,
            'dipesan_pada' => now(),
        ]);
        DB::table('detail_pesanan_pupuk')->insert([
            'id_pesanan_pupuk' => $cancelledOrder->id,
            'id_produk_pupuk' => $urea->id,
            'nama_produk' => $urea->nama_produk,
            'jumlah' => 9,
            'satuan' => '50 kg',
            'harga_satuan' => 0,
            'subtotal' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherOrder = PesananPupuk::create([
            'nomor_pesanan' => 'PPK-LAPORAN-003',
            'id_petani' => $otherFarmer->id,
            'metode_pembayaran' => 'tunai',
            'status_pesanan' => 'diterima',
            'total_harga' => 0,
            'dipesan_pada' => now(),
        ]);
        DB::table('detail_pesanan_pupuk')->insert([
            'id_pesanan_pupuk' => $otherOrder->id,
            'id_produk_pupuk' => $urea->id,
            'nama_produk' => $urea->nama_produk,
            'jumlah' => 1,
            'satuan' => '50 kg',
            'harga_satuan' => 0,
            'subtotal' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->getJson('/api/admin/bootstrap')->assertOk();
        $reportFarmer = collect($response->json('users'))->firstWhere('id', $farmer->id);

        $this->assertSame(2, $reportFarmer['reportFertilizerPurchases'][$year]['urea']);
        $this->assertSame(3, $reportFarmer['reportFertilizerPurchases'][$year]['npk_formula']);
    }

    public function test_admin_can_update_application_and_payment_settings(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)->putJson('/api/admin/pengaturan', [
            'appName' => 'POKTAN Digital',
            'location' => 'Desa Lancang Kuning',
            'marketplace' => 'Perawatan',
            'maintenance' => 'Aktif',
            'maintenanceMessage' => 'Pembaruan sistem sedang berlangsung.',
            'buyerPaymentDisabledMethods' => ['QRIS'],
            'farmerPaymentDisabledMethods' => ['Transfer'],
        ])->assertOk()
            ->assertJsonPath('appName', 'POKTAN Digital')
            ->assertJsonPath('marketplace', 'Perawatan');

        $this->assertDatabaseHas('pengaturan_aplikasi', [
            'id' => 1,
            'nama_aplikasi' => 'POKTAN Digital',
            'lokasi_aplikasi' => 'Desa Lancang Kuning',
            'status_marketplace' => 'perawatan',
            'maintenance_aktif' => true,
        ]);
        $this->assertDatabaseHas('metode_pembayaran', [
            'konteks' => 'marketplace_pembeli',
            'metode' => 'qris',
            'aktif' => false,
        ]);
        $this->assertDatabaseHas('metode_pembayaran', [
            'konteks' => 'pupuk_petani',
            'metode' => 'transfer',
            'aktif' => false,
        ]);
    }

    public function test_admin_can_manage_fertilizer_notification_and_content(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $fertilizerResponse = $this->actingAs($admin)->postJson('/api/admin/pupuk', [
            'nama_produk' => 'Dolomit',
            'harga' => 75000,
            'jumlah_stok' => 12,
            'ukuran_kemasan' => '25 kg',
            'deskripsi' => 'Membantu menetralkan keasaman tanah.',
        ])->assertCreated()->assertJsonPath('package', '25 kg');

        $fertilizerId = $fertilizerResponse->json('id');
        $this->actingAs($admin)->postJson("/api/admin/pupuk/{$fertilizerId}", [
            'nama_produk' => 'Dolomit Pertanian',
            'harga' => 80000,
            'jumlah_stok' => 10,
            'ukuran_kemasan' => '25 kg',
            'deskripsi' => 'Dolomit untuk lahan pertanian.',
        ])->assertOk();
        $this->assertDatabaseHas('produk_pupuk', [
            'id' => $fertilizerId,
            'nama_produk' => 'Dolomit Pertanian',
        ]);

        $notificationResponse = $this->actingAs($admin)->postJson('/api/admin/notifikasi', [
            'judul' => 'Stok pupuk tersedia',
            'kategori' => 'pupuk',
            'pesan' => 'Silakan melakukan pemesanan melalui aplikasi.',
            'target_peran' => 'petani',
        ])->assertCreated();
        $this->assertDatabaseHas('notifikasi_aplikasi', [
            'id' => $notificationResponse->json('id'),
            'target_peran' => 'petani',
        ]);

        $contentResponse = $this->actingAs($admin)->postJson('/api/admin/konten', [
            'kategori' => 'edukasi',
            'judul' => 'Cara Menanam Padi',
            'jenis_konten' => 'panduan',
            'deskripsi' => 'Panduan singkat penanaman padi.',
            'tautan' => '/edukasi',
        ])->assertCreated()
            ->assertJsonPath('image', '/assets/edukasi/orang-edukasi.png');
        $this->assertDatabaseHas('konten_aplikasi', [
            'id' => $contentResponse->json('id'),
            'judul' => 'Cara Menanam Padi',
            'gambar' => '/assets/edukasi/orang-edukasi.png',
        ]);

        $pestContentResponse = $this->actingAs($admin)->postJson('/api/admin/konten', [
            'kategori' => 'hama_penyakit',
            'judul' => 'Pengendalian Hama Padi',
            'jenis_konten' => 'solusi',
            'deskripsi' => 'Panduan pengendalian hama pada tanaman padi.',
            'tautan' => '/hama-penyakit',
        ])->assertCreated()
            ->assertJsonPath('image', '/assets/hama-penyakit/hama.png');

        $this->assertDatabaseHas('konten_aplikasi', [
            'id' => $pestContentResponse->json('id'),
            'kategori' => 'hama_penyakit',
            'gambar' => '/assets/hama-penyakit/hama.png',
        ]);

        $this->actingAs($admin)->postJson('/api/admin/konten', [
            'kategori' => 'edukasi',
            'judul' => 'Link Tidak Aman',
            'jenis_konten' => 'artikel',
            'deskripsi' => 'Konten dengan link tidak aman.',
            'tautan' => 'javascript:alert(1)',
        ])->assertUnprocessable();

        $this->actingAs($admin)->deleteJson("/api/admin/pupuk/{$fertilizerId}")->assertOk();
        $this->assertSoftDeleted(ProdukPupuk::withTrashed()->findOrFail($fertilizerId));
        $this->assertNotNull(NotifikasiAplikasi::find($notificationResponse->json('id')));
        $this->assertNotNull(KontenAplikasi::find($contentResponse->json('id')));
    }

    public function test_admin_order_status_transitions_are_guarded(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $order = PesananPupuk::create([
            'nomor_pesanan' => 'PP-TEST-001',
            'id_petani' => $farmer->id,
            'metode_pembayaran' => 'tunai',
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu',
            'total_harga' => 100000,
            'dipesan_pada' => now(),
        ]);

        $this->actingAs($admin)
            ->patchJson("/api/admin/pesanan-pupuk/{$order->id}", ['status' => 'diterima'])
            ->assertOk();

        $this->actingAs($admin)
            ->patchJson("/api/admin/pesanan-pupuk/{$order->id}", ['status' => 'selesai'])
            ->assertOk();

        $this->assertDatabaseHas('pesanan_pupuk', [
            'id' => $order->id,
            'status_pesanan' => 'selesai',
            'status_pembayaran' => 'lunas',
        ]);

        $this->actingAs($admin)
            ->patchJson("/api/admin/pesanan-pupuk/{$order->id}", ['status' => 'diterima'])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Perubahan status pesanan tidak valid.');
    }

    public function test_profile_stores_and_returns_the_location_name(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        Http::fake([
            'weather.ewalabs.com/*' => Http::response([
                'data' => [
                    'location' => 'Desa Lancang Kuning, Kabupaten Pelalawan',
                    'forecast' => [[
                        'local_datetime' => now()->addHour()->toIso8601String(),
                        'weather' => 'Partly Cloudy',
                        'temperature' => 29,
                        'humidity' => 78,
                        'wind_speed' => 8,
                        'wind_direction' => 'Timur',
                    ]],
                ],
            ]),
        ]);

        $weather = $this->actingAs($farmer)
            ->getJson('/api/cuaca-lokasi?lat=0.1234&lng=101.5678')
            ->assertOk()
            ->assertJsonPath('lokasi.nama', 'Desa Lancang Kuning, Kabupaten Pelalawan');

        $this->actingAs($farmer)->putJson('/api/profile/lokasi', [
            'latitude' => $weather->json('lokasi.lat'),
            'longitude' => $weather->json('lokasi.lng'),
            'nama_lokasi' => $weather->json('lokasi.nama'),
        ])->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $farmer->id,
            'nama_lokasi' => 'Desa Lancang Kuning, Kabupaten Pelalawan',
        ]);
        $this->actingAs($farmer)
            ->getJson('/api/profile')
            ->assertJsonPath('locationName', 'Desa Lancang Kuning, Kabupaten Pelalawan');
    }

    public function test_farmer_and_buyer_can_update_their_account_password(): void
    {
        foreach (['petani', 'pembeli'] as $role) {
            $user = User::factory()->create([
                'peran' => $role,
                'status' => 'aktif',
                'password' => 'password123',
            ]);

            $this->actingAs($user)
                ->putJson('/api/profile/password', [
                    'current_password' => 'password-salah',
                    'password' => 'passwordBaru123',
                    'password_confirmation' => 'passwordBaru123',
                ])
                ->assertUnprocessable()
                ->assertJsonValidationErrors('current_password');

            $this->actingAs($user)
                ->putJson('/api/profile/password', [
                    'current_password' => 'password123',
                    'password' => 'passwordBaru123',
                    'password_confirmation' => 'passwordBaru123',
                ])
                ->assertOk()
                ->assertJsonPath('message', 'Password akun berhasil diperbarui.');

            $this->assertTrue(Hash::check('passwordBaru123', $user->fresh()->password));
            $this->assertNotNull($user->fresh()->password_updated_at);
        }
    }

    public function test_farmer_weather_page_contains_location_controls_and_csrf_protection(): void
    {
        $farmer = User::factory()->create([
            'peran' => 'petani',
            'status' => 'aktif',
            'latitude' => 1.3648,
            'longitude' => 109.3111,
            'nama_lokasi' => 'Tanjung Bugis',
        ]);

        $this->actingAs($farmer)
            ->get('/cuaca')
            ->assertOk()
            ->assertSee('data-halaman-cuaca', false)
            ->assertDontSee('data-cuaca-refresh', false)
            ->assertDontSee('5 hari')
            ->assertSee('data-cuaca-location-button', false)
            ->assertSee('name="csrf-token"', false);
    }

    public function test_marketplace_cancellation_restores_stock_and_terminal_status_cannot_change(): void
    {
        $farmer = User::factory()->create(['peran' => 'petani', 'status' => 'aktif']);
        $buyer = User::factory()->create(['peran' => 'pembeli', 'status' => 'aktif']);
        $product = ProdukMarketplace::create([
            'id_penjual' => $farmer->id,
            'nama_produk' => 'Beras Premium',
            'harga' => 16000,
            'jumlah_stok' => 20,
            'satuan' => 'kg',
            'aktif' => true,
        ]);

        $orderId = $this->actingAs($buyer)->postJson('/api/pembeli/pesanan', [
            'id_produk' => $product->id,
            'jumlah' => 4,
            'metode_pembayaran' => 'tunai',
        ])->assertCreated()->json('id');

        $this->actingAs($farmer)
            ->patchJson("/api/marketplace-pesanan/{$orderId}", ['status' => 'disetujui'])
            ->assertOk();
        $this->assertSame(16.0, (float) $product->fresh()->jumlah_stok);

        $this->actingAs($farmer)
            ->patchJson("/api/marketplace-pesanan/{$orderId}", ['status' => 'dibatalkan'])
            ->assertOk();
        $this->assertSame(20.0, (float) $product->fresh()->jumlah_stok);

        $this->actingAs($farmer)
            ->patchJson("/api/marketplace-pesanan/{$orderId}", ['status' => 'selesai'])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Perubahan status pesanan tidak valid.');
    }

    public function test_web_responses_include_production_security_headers(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
    }
}
