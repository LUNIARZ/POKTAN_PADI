<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LahanPetani extends Model
{
    use SoftDeletes;

    protected $table = 'lahan_petani';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['luas_meter' => 'integer'];
    }

    public function petani(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_petani');
    }
}
