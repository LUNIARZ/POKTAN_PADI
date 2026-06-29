<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalTanam extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_tanam';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_semai' => 'date:Y-m-d',
            'perkiraan_tanggal_tanam' => 'date:Y-m-d',
            'perkiraan_tanggal_panen' => 'date:Y-m-d',
            'jumlah_tahap_selesai' => 'integer',
            'persentase_progres' => 'integer',
            'dimulai_pada' => 'datetime',
            'diselesaikan_pada' => 'datetime',
        ];
    }

    public function petani(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_petani');
    }

    public function tahapan(): HasMany
    {
        return $this->hasMany(ProgresTahapTanam::class, 'id_jadwal_tanam')->orderBy('urutan');
    }
}
