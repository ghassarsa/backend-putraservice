<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    protected $fillable = ['category_id', 'image'];

    protected $casts = [
        'image' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
