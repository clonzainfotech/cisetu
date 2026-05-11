<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Home extends Model
{
    protected $fillable = [
        'village_id',
        'user_id',
        'property_no',
        'house_no',
        'owner',
        'occupant',
        'address',
        'total',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
