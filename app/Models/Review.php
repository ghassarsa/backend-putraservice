<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class review extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'komentar',
        'rating',
        'validasi',
        'device_token',
        'perubahan',
    ];
}
