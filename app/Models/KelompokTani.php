<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokTani extends Model
{
    protected $table = 'kelompok_tani';

    protected $fillable = [
        'nama',
        'kecamatan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function petani()
    {
        return $this->hasMany(User::class, 'id_kelompok_tani');
    }
}