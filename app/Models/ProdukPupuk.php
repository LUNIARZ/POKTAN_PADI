<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukPupuk extends Model
{
    use SoftDeletes;

    protected $table = 'produk_pupuk';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'jumlah_stok' => 'integer',
            'aktif' => 'boolean',
        ];
    }
}
