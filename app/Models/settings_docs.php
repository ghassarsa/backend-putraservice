<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class settings_docs extends Model
{
    protected $fillable = ['key', 'value', 'category_id'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
