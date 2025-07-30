<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
    ];
}
