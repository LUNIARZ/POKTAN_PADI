<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilPetani extends Model
{
    protected $table = 'profil_petani';

    protected $fillable = ['id_pengguna', 'luas_lahan_meter'];

    protected function casts(): array
    {
        return ['luas_lahan_meter' => 'integer'];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
