<?php

namespace App\Models;

use Database\Factories\TeamSubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'team_id',
    'plan_id',
    'status',
    'starts_at',
    'ends_at',
    'grace_ends_at',
    'billing_reference',
])]
class TeamSubscription extends Model
{
    /** @use HasFactory<TeamSubscriptionFactory> */
    use HasFactory;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'grace_ends_at' => 'date',
        ];
    }
}
