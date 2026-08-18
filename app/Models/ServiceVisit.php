<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceVisit extends Model
{
    protected $fillable = ['subscription_id', 'visit_date', 'technician_notes', 'status'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
