<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifikasiAplikasi extends Model
{
    use SoftDeletes;

    protected $table = 'notifikasi_aplikasi';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'data_tambahan' => 'array',
            'diterbitkan_pada' => 'datetime',
            'berakhir_pada' => 'datetime',
        ];
    }

    public function penerima(): HasMany
    {
        return $this->hasMany(PenerimaNotifikasi::class, 'id_notifikasi');
    }
}
