<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerimaNotifikasi extends Model
{
    protected $table = 'penerima_notifikasi';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dibaca_pada' => 'datetime',
        ];
    }

    public function notifikasi(): BelongsTo
    {
        return $this->belongsTo(NotifikasiAplikasi::class, 'id_notifikasi');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
