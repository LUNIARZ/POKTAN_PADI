<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananPupuk extends Model
{
    use SoftDeletes;

    protected $table = 'pesanan_pupuk';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dipesan_pada' => 'datetime',
            'dikonfirmasi_pada' => 'datetime',
            'diselesaikan_pada' => 'datetime',
        ];
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesananPupuk::class, 'id_pesanan_pupuk');
    }

    public function petani(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_petani')->withTrashed();
    }
}
