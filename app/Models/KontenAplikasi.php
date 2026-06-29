<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontenAplikasi extends Model
{
    use SoftDeletes;

    protected $table = 'konten_aplikasi';

    protected $guarded = [];
}
