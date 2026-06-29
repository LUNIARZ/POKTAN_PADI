<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresTahapTanam extends Model
{
    protected $table = 'progres_tahap_tanam';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_mulai_target' => 'date:Y-m-d',
            'tanggal_selesai_target' => 'date:Y-m-d',
            'tanggal_mulai_aktual' => 'date:Y-m-d',
            'tanggal_selesai_aktual' => 'date:Y-m-d',
        ];
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalTanam::class, 'id_jadwal_tanam');
    }
}
