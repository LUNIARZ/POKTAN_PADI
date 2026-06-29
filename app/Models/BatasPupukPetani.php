<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatasPupukPetani extends Model
{
    protected $table = 'batas_pupuk_petani';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'maksimal_jumlah' => 'integer',
            'aktif' => 'boolean',
        ];
    }
}
