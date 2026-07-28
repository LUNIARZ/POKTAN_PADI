<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukMarketplace extends Model
{
    use SoftDeletes;

    protected $table = 'produk_marketplace';

    protected $fillable = [
        'id_penjual',
        'nama_produk',
        'deskripsi',
        'alamat_produk',
        'harga',
        'jumlah_stok',
        'satuan',
        'gambar_produk',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'jumlah_stok' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penjual');
    }
}
