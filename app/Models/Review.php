<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
