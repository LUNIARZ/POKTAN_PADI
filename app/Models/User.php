<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'nomor_hp',
        'nik',
        'alamat',
        'nama_lokasi',
        'foto_profil',
        'peran',
        'status',
        'id_kelompok_tani',
        'latitude',
        'longitude',
        'password',
        'password_updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password_updated_at' => 'datetime',
            'password' => 'hashed',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function profilPetani(): HasOne
    {
        return $this->hasOne(ProfilPetani::class, 'id_pengguna');
    }

    public function profilPembeli(): HasOne
    {
        return $this->hasOne(ProfilPembeli::class, 'id_pengguna');
    }

    public function lahan(): HasMany
    {
        return $this->hasMany(LahanPetani::class, 'id_petani');
    }

    public function produkMarketplace(): HasMany
    {
        return $this->hasMany(ProdukMarketplace::class, 'id_penjual');
    }

    public function isRole(string ...$roles): bool
    {
        return in_array($this->peran, $roles, true);
    }

    public function kelompokTani(): BelongsTo
    {
        return $this->belongsTo(KelompokTani::class, 'id_kelompok_tani');
    }
}
