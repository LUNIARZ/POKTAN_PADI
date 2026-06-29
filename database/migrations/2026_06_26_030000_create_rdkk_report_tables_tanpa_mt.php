<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rdkk_laporan')) {
            Schema::create('rdkk_laporan', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('tahun');
                $table->string('kecamatan', 150);
                $table->string('desa_kelurahan', 150);
                $table->string('kelompok_tani', 150);
                $table->string('subsektor', 100)->default('Tanaman Pangan');
                $table->string('komoditas', 100)->default('Padi');
                $table->string('kios', 150)->nullable();
                $table->foreignId('dicetak_oleh')->nullable()
                    ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
                $table->dateTime('dicetak_pada')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->index('tahun');
                $table->index('kelompok_tani');
            });
        }

        if (! Schema::hasTable('rdkk_detail_laporan')) {
            Schema::create('rdkk_detail_laporan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_rdkk_laporan')
                    ->constrained('rdkk_laporan')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreignId('id_petani')->nullable()
                    ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
                $table->char('nik', 16)->nullable();
                $table->string('nama_petani', 150);
                $table->decimal('rencana_tanam_ha', 8, 3)->unsigned()->default(0);

                // Project hanya menggunakan jumlah total, jadi MT1, MT2, dan MT3 tidak dibuat.
                $table->unsignedInteger('urea_jumlah')->default(0);
                $table->unsignedInteger('npk_jumlah')->default(0);
                $table->unsignedInteger('npk_formula_jumlah')->default(0);
                $table->unsignedInteger('organik_jumlah')->default(0);
                $table->unsignedInteger('za_jumlah')->default(0);

                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->unique(['id_rdkk_laporan', 'id_petani']);
                $table->index('nik');
            });

            return;
        }

        $this->ensureExistingDetailTableUsesHectareWithoutMt();
    }

    public function down(): void
    {
        Schema::dropIfExists('rdkk_detail_laporan');
        Schema::dropIfExists('rdkk_laporan');
    }

    private function ensureExistingDetailTableUsesHectareWithoutMt(): void
    {
        $renamedAreaFromMeter = false;

        if (! Schema::hasColumn('rdkk_detail_laporan', 'rencana_tanam_ha')) {
            $legacyAreaColumn = collect(['rencana_tanam', 'luas_tanam'])
                ->map(fn ($prefix) => "{$prefix}_meter")
                ->first(fn ($column) => Schema::hasColumn('rdkk_detail_laporan', $column));

            if ($legacyAreaColumn) {
                Schema::table('rdkk_detail_laporan', function (Blueprint $table) use ($legacyAreaColumn) {
                    $table->renameColumn($legacyAreaColumn, 'rencana_tanam_ha');
                });

                $renamedAreaFromMeter = true;
            } else {
                Schema::table('rdkk_detail_laporan', function (Blueprint $table) {
                    $table->decimal('rencana_tanam_ha', 8, 3)->unsigned()->default(0)->after('nama_petani');
                });
            }
        }

        $mtColumns = collect(['urea', 'npk', 'npk_formula', 'organik', 'za'])
            ->flatMap(fn ($fertilizer) => collect(range(1, 3))->map(fn ($season) => "{$fertilizer}_mt{$season}"))
            ->filter(fn ($column) => Schema::hasColumn('rdkk_detail_laporan', $column))
            ->values()
            ->all();

        if ($mtColumns !== []) {
            Schema::table('rdkk_detail_laporan', function (Blueprint $table) use ($mtColumns) {
                $table->dropColumn($mtColumns);
            });
        }

        foreach (['urea_jumlah', 'npk_jumlah', 'npk_formula_jumlah', 'organik_jumlah', 'za_jumlah'] as $column) {
            if (! Schema::hasColumn('rdkk_detail_laporan', $column)) {
                Schema::table('rdkk_detail_laporan', function (Blueprint $table) use ($column) {
                    $table->unsignedInteger($column)->default(0);
                });
            }
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE rdkk_detail_laporan MODIFY rencana_tanam_ha DECIMAL(8,3) UNSIGNED NOT NULL DEFAULT 0');

            if ($renamedAreaFromMeter) {
                DB::statement('UPDATE rdkk_detail_laporan SET rencana_tanam_ha = ROUND(rencana_tanam_ha / 10000, 3)');
            }
        }
    }
};
