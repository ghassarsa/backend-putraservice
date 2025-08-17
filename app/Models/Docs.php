<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
