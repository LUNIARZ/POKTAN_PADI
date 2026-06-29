<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropForeignIfExists('jadwal_tanam', ['id_lahan']);
        $this->dropForeignIfExists('hasil_panen_padi', ['id_lahan']);
        $this->dropForeignIfExists('hasil_panen_padi', ['id_jadwal_tanam']);

        $this->dropColumns('profil_petani', ['nama_kelompok_tani', 'catatan']);
        $this->dropColumns('lahan_petani', ['desa', 'kecamatan', 'kabupaten', 'provinsi', 'latitude', 'longitude', 'catatan']);
        $this->dropColumns('jadwal_tanam', ['id_lahan', 'judul', 'jenis_bibit', 'catatan']);
        $this->dropColumns('progres_tahap_tanam', ['catatan', 'petunjuk', 'detail_petunjuk']);
        $this->dropColumns('hasil_panen_padi', ['id_lahan', 'id_jadwal_tanam', 'nama_panen', 'kualitas', 'catatan']);
        $this->dropColumns('produk_pupuk', ['satuan_stok']);
        $this->dropColumns('batas_pupuk_petani', ['periode', 'berlaku_mulai', 'berlaku_sampai']);
        $this->dropColumns('pesanan_pupuk', ['catatan_petani', 'catatan_admin']);
        $this->dropColumns('pesanan_marketplace', ['catatan_penjual']);
        $this->dropColumns('konten_aplikasi', ['isi_konten']);
        $this->dropColumns('pengaturan_aplikasi', ['catatan_admin']);
        $this->dropColumns('metode_pembayaran', ['instruksi']);
    }

    public function down(): void
    {
        // Field ini sudah tidak digunakan aplikasi. Data lama tidak dapat dipulihkan secara aman.
    }

    private function dropColumns(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $existing = array_values(array_filter(
            $columns,
            fn (string $column): bool => Schema::hasColumn($table, $column)
        ));

        if ($existing === []) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($existing): void {
            $table->dropColumn($existing);
        });
    }

    private function dropForeignIfExists(string $table, array $columns): void
    {
        if (DB::getDriverName() === 'sqlite' || ! Schema::hasTable($table)) {
            return;
        }

        if (! collect($columns)->every(fn (string $column): bool => Schema::hasColumn($table, $column))) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $table) use ($columns): void {
                $table->dropForeign($columns);
            });
        } catch (Throwable) {
            //
        }
    }
};
