<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananMarketplace extends Model
{
    use SoftDeletes;

    protected $table = 'pesanan_marketplace';

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
        return $this->hasMany(DetailPesananMarketplace::class, 'id_pesanan_marketplace');
    }

    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pembeli');
    }
}
