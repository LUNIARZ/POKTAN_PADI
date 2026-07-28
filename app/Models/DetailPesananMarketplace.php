<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesananMarketplace extends Model
{
    protected $table = 'detail_pesanan_marketplace';

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
