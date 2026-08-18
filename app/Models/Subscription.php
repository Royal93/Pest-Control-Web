<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'status', 'started_at', 'next_visit_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function serviceVisits()
    {
        return $this->hasMany(ServiceVisit::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
