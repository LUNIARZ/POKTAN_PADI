<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesananPupuk extends Model
{
    protected $table = 'detail_pesanan_pupuk';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }
}
