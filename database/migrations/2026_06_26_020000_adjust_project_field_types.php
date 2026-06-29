<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $this->normaliseIntegerColumn('profil_petani', 'luas_lahan_meter');
        $this->normaliseIntegerColumn('lahan_petani', 'luas_meter');
        $this->normaliseIntegerColumn('jadwal_tanam', 'persentase_progres', 100);
        $this->normaliseIntegerColumn('produk_pupuk', 'jumlah_stok');
        $this->normaliseIntegerColumn('detail_pesanan_pupuk', 'jumlah');
        $this->normaliseIntegerColumn('produk_marketplace', 'jumlah_stok');
        $this->normaliseIntegerColumn('detail_pesanan_marketplace', 'jumlah');

        $this->changeToUnsignedInteger('profil_petani', 'luas_lahan_meter');
        $this->changeToUnsignedInteger('lahan_petani', 'luas_meter');
        $this->changeToUnsignedTinyInteger('jadwal_tanam', 'persentase_progres');
        $this->changeToUnsignedInteger('produk_pupuk', 'jumlah_stok');
        $this->changeToUnsignedInteger('detail_pesanan_pupuk', 'jumlah', 1);
        $this->changeToUnsignedInteger('produk_marketplace', 'jumlah_stok');
        $this->changeToUnsignedInteger('detail_pesanan_marketplace', 'jumlah', 1);
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $this->changeToDecimal('profil_petani', 'luas_lahan_meter', 12, 2);
        $this->changeToDecimal('lahan_petani', 'luas_meter', 12, 2);
        $this->changeToDecimal('jadwal_tanam', 'persentase_progres', 5, 2);
        $this->changeToDecimal('produk_pupuk', 'jumlah_stok', 14, 2);
        $this->changeToDecimal('detail_pesanan_pupuk', 'jumlah', 12, 2, 1);
        $this->changeToDecimal('produk_marketplace', 'jumlah_stok', 14, 2);
        $this->changeToDecimal('detail_pesanan_marketplace', 'jumlah', 14, 2, 1);
    }

    private function normaliseIntegerColumn(string $table, string $column, ?int $max = null): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        $expression = "GREATEST(0, ROUND(`{$column}`))";
        if ($max !== null) {
            $expression = "LEAST({$max}, {$expression})";
        }

        DB::table($table)->update([$column => DB::raw($expression)]);
    }

    private function changeToUnsignedInteger(string $tableName, string $column, int $default = 0): void
    {
        $this->changeColumn($tableName, $column, function (Blueprint $table) use ($column, $default): void {
            $table->unsignedInteger($column)->default($default)->change();
        });
    }

    private function changeToUnsignedTinyInteger(string $tableName, string $column): void
    {
        $this->changeColumn($tableName, $column, function (Blueprint $table) use ($column): void {
            $table->unsignedTinyInteger($column)->default(0)->change();
        });
    }

    private function changeToDecimal(string $tableName, string $column, int $total, int $places, int $default = 0): void
    {
        $this->changeColumn($tableName, $column, function (Blueprint $table) use ($column, $total, $places, $default): void {
            $table->decimal($column, $total, $places)->unsigned()->default($default)->change();
        });
    }

    private function changeColumn(string $tableName, string $column, callable $definition): void
    {
        if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, $column)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($column, $definition): void {
            $definition($table, $column);
        });
    }
};
