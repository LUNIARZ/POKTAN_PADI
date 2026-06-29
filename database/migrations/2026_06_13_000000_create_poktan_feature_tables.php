<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Blueprint::macro('poktanTimestamps', function (): void {
            $this->timestamp('created_at')->nullable()->useCurrent();
            $this->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('profil_petani', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->unique()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('luas_lahan_meter')->default(0);
            $table->poktanTimestamps();
        });

        Schema::create('profil_pembeli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->unique()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_gudang', 150);
            $table->text('alamat_gudang')->nullable();
            $table->poktanTimestamps();
            $table->index('nama_gudang');
        });

        Schema::create('lahan_petani', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petani')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_lahan', 150)->default('Lahan Padi');
            $table->string('nama_pemilik', 150)->nullable();
            $table->unsignedInteger('luas_meter')->default(0);
            $table->text('alamat')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_petani', 'status']);
        });

        Schema::create('jadwal_tanam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petani')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('tanggal_semai');
            $table->date('perkiraan_tanggal_tanam')->nullable();
            $table->date('perkiraan_tanggal_panen')->nullable();
            $table->enum('tahap_aktif', ['pembibitan', 'penanaman', 'perawatan_tanaman', 'panen', 'selesai'])
                ->default('pembibitan');
            $table->unsignedTinyInteger('jumlah_tahap_selesai')->default(0);
            $table->unsignedTinyInteger('persentase_progres')->default(0);
            $table->enum('status', ['rencana', 'aktif', 'selesai', 'batal'])->default('rencana');
            $table->dateTime('dimulai_pada')->nullable();
            $table->dateTime('diselesaikan_pada')->nullable();
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_petani', 'status']);
            $table->index(['id_petani', 'created_at']);
            $table->index('tanggal_semai');
        });

        Schema::create('progres_tahap_tanam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal_tanam')->constrained('jadwal_tanam')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('urutan');
            $table->enum('nama_tahap', ['pembibitan', 'penanaman', 'perawatan_tanaman', 'panen']);
            $table->string('rentang_hari', 100)->nullable();
            $table->date('tanggal_mulai_target')->nullable();
            $table->date('tanggal_selesai_target')->nullable();
            $table->date('tanggal_mulai_aktual')->nullable();
            $table->date('tanggal_selesai_aktual')->nullable();
            $table->enum('status', ['menunggu', 'aktif', 'selesai', 'dilewati'])->default('menunggu');
            $table->poktanTimestamps();
            $table->unique(['id_jadwal_tanam', 'urutan']);
            $table->index('status');
        });

        Schema::create('hasil_panen_padi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petani')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('jumlah_kg', 14, 2)->unsigned()->default(0);
            $table->string('jenis_bibit', 120)->nullable();
            $table->date('tanggal_panen');
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_petani', 'tanggal_panen']);
        });

        Schema::create('produk_pupuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nama_produk', 150);
            $table->string('slug', 180)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('ukuran_kemasan', 80)->nullable();
            $table->decimal('harga', 15, 2)->unsigned()->default(0);
            $table->unsignedInteger('jumlah_stok')->default(0);
            $table->string('gambar_produk', 500)->nullable();
            $table->boolean('aktif')->default(true);
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['aktif', 'nama_produk']);
        });

        Schema::create('batas_pupuk_petani', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petani')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_produk_pupuk')->constrained('produk_pupuk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('maksimal_jumlah')->default(0);
            $table->boolean('aktif')->default(true);
            $table->poktanTimestamps();
            $table->unique(['id_petani', 'id_produk_pupuk']);
            $table->index('aktif');
        });

        Schema::create('pesanan_pupuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 40)->unique();
            $table->foreignId('id_petani')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->enum('status_pembayaran', ['menunggu', 'lunas', 'gagal', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pesanan', ['menunggu', 'diterima', 'ditolak', 'selesai', 'dibatalkan'])
                ->default('menunggu');
            $table->decimal('total_harga', 15, 2)->unsigned()->default(0);
            $table->dateTime('dipesan_pada')->useCurrent();
            $table->dateTime('dikonfirmasi_pada')->nullable();
            $table->dateTime('diselesaikan_pada')->nullable();
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_petani', 'status_pesanan']);
            $table->index(['id_petani', 'dipesan_pada']);
            $table->index('dipesan_pada');
        });

        Schema::create('detail_pesanan_pupuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pesanan_pupuk')->constrained('pesanan_pupuk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_produk_pupuk')->nullable()->constrained('produk_pupuk')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nama_produk', 150);
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('satuan', 50)->nullable();
            $table->decimal('harga_satuan', 15, 2)->unsigned()->default(0);
            $table->decimal('subtotal', 15, 2)->unsigned()->default(0);
            $table->poktanTimestamps();
        });

        Schema::create('produk_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penjual')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();
            $table->text('alamat_produk')->nullable();
            $table->decimal('harga', 15, 2)->unsigned()->nullable();
            $table->unsignedInteger('jumlah_stok')->default(0);
            $table->string('satuan', 30)->default('kg');
            $table->string('gambar_produk', 500)->nullable();
            $table->boolean('aktif')->default(true);
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_penjual', 'aktif', 'created_at']);
            $table->index(['aktif', 'created_at']);
            $table->index('nama_produk');
        });

        Schema::create('pesanan_marketplace', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 40)->unique();
            $table->foreignId('id_pembeli')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_penjual')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('nama_pembeli_snapshot', 150);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->enum('status_pembayaran', ['menunggu', 'lunas', 'gagal', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pesanan', ['menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])
                ->default('menunggu');
            $table->text('catatan_pembeli')->nullable();
            $table->decimal('total_harga', 15, 2)->unsigned()->default(0);
            $table->dateTime('dipesan_pada')->useCurrent();
            $table->dateTime('dikonfirmasi_pada')->nullable();
            $table->dateTime('diselesaikan_pada')->nullable();
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['id_pembeli', 'status_pesanan']);
            $table->index(['id_penjual', 'status_pesanan']);
            $table->index(['id_pembeli', 'dipesan_pada']);
            $table->index(['id_penjual', 'dipesan_pada']);
            $table->index('dipesan_pada');
        });

        Schema::create('detail_pesanan_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pesanan_marketplace')->constrained('pesanan_marketplace')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_produk_marketplace')->nullable()->constrained('produk_marketplace')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nama_produk', 150);
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('satuan', 30)->default('kg');
            $table->decimal('harga_satuan', 15, 2)->unsigned()->default(0);
            $table->decimal('subtotal', 15, 2)->unsigned()->default(0);
            $table->poktanTimestamps();
        });

        Schema::create('notifikasi_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('kategori', ['transaksi', 'pupuk', 'cuaca', 'edukasi', 'hama_penyakit', 'sistem'])
                ->default('sistem');
            $table->enum('target_peran', ['semua', 'admin', 'petani', 'pembeli', 'khusus'])->default('semua');
            $table->string('judul', 180);
            $table->text('pesan');
            $table->string('tautan', 500)->nullable();
            $table->json('data_tambahan')->nullable();
            $table->dateTime('diterbitkan_pada')->nullable();
            $table->dateTime('berakhir_pada')->nullable();
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['kategori', 'diterbitkan_pada']);
            $table->index(['target_peran', 'diterbitkan_pada']);
        });

        Schema::create('penerima_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_notifikasi')->constrained('notifikasi_aplikasi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_pengguna')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('dibaca_pada')->nullable();
            $table->poktanTimestamps();
            $table->unique(['id_notifikasi', 'id_pengguna']);
            $table->index(['id_pengguna', 'dibaca_pada']);
        });

        Schema::create('konten_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('kategori', ['edukasi', 'hama_penyakit']);
            $table->enum('jenis_konten', ['artikel', 'video', 'panduan', 'solusi'])->default('artikel');
            $table->string('judul', 200);
            $table->string('slug', 220)->unique();
            $table->text('deskripsi');
            $table->string('gambar', 500)->nullable();
            $table->string('tautan', 500)->nullable();
            $table->enum('status', ['draft', 'terbit', 'arsip'])->default('terbit');
            $table->dateTime('diterbitkan_pada')->nullable();
            $table->poktanTimestamps();
            $table->softDeletes();
            $table->index(['kategori', 'status', 'diterbitkan_pada']);
        });

        Schema::create('pengaturan_aplikasi', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->default(1)->primary();
            $table->string('nama_aplikasi', 150)->default('POKTAN Lancang Kuning');
            $table->string('lokasi_aplikasi')->nullable();
            $table->enum('status_marketplace', ['aktif', 'perawatan', 'nonaktif'])->default('aktif');
            $table->boolean('maintenance_aktif')->default(false);
            $table->text('pesan_maintenance')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->poktanTimestamps();
        });

        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->enum('konteks', ['marketplace_pembeli', 'pupuk_petani']);
            $table->enum('metode', ['tunai', 'transfer', 'qris']);
            $table->string('nama_tampilan', 80);
            $table->boolean('aktif')->default(true);
            $table->poktanTimestamps();
            $table->unique(['konteks', 'metode']);
            $table->index('aktif');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
        Schema::dropIfExists('pengaturan_aplikasi');
        Schema::dropIfExists('konten_aplikasi');
        Schema::dropIfExists('penerima_notifikasi');
        Schema::dropIfExists('notifikasi_aplikasi');
        Schema::dropIfExists('detail_pesanan_marketplace');
        Schema::dropIfExists('pesanan_marketplace');
        Schema::dropIfExists('produk_marketplace');
        Schema::dropIfExists('detail_pesanan_pupuk');
        Schema::dropIfExists('pesanan_pupuk');
        Schema::dropIfExists('batas_pupuk_petani');
        Schema::dropIfExists('produk_pupuk');
        Schema::dropIfExists('hasil_panen_padi');
        Schema::dropIfExists('progres_tahap_tanam');
        Schema::dropIfExists('jadwal_tanam');
        Schema::dropIfExists('lahan_petani');
        Schema::dropIfExists('profil_pembeli');
        Schema::dropIfExists('profil_petani');
    }
};
