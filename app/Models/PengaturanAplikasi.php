<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanAplikasi extends Model
{
    protected $table = 'pengaturan_aplikasi';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['maintenance_aktif' => 'boolean'];
    }
}
