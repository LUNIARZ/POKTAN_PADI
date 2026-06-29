<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }
}
