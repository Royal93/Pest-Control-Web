<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pest extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'photo_path', 'category', 'featured'];

    protected $casts = [
        'featured' => 'boolean',
    ];
}
