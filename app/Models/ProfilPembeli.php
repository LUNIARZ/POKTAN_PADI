<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilPembeli extends Model
{
    protected $table = 'profil_pembeli';

    protected $fillable = ['id_pengguna', 'nama_gudang', 'alamat_gudang'];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
