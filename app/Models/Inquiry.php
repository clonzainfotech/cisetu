<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'village_name',
        'district_name',
        'plan_id',
        'message',
        'status',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
