<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPanen extends Model
{
    use SoftDeletes;

    protected $table = 'hasil_panen_padi';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['tanggal_panen' => 'date:Y-m-d'];
    }
}
