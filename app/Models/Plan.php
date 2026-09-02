<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'key', 'name', 'price', 'billing_cycle', 'description', 'meta',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'meta' => 'array',
    ];
}
