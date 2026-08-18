<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pest extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'photo_path', 'category', 'featured',
        'infestation_signs', 'health_risks', 'business_impact',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    /**
     * Use the slug for route binding instead of the numeric id,
     * so /services/residential/ants works instead of /services/residential/2.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
