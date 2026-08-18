<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'description', 'common_pests'];

    protected $casts = [
        'common_pests' => 'array',
    ];
}
