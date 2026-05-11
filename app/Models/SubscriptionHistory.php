<?php

namespace App\Models;

use Database\Factories\SubscriptionHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'village_id',
    'plan_id',
    'event_type',
    'previous_ends_at',
    'new_ends_at',
    'performed_by_user_id',
    'note',
])]
class SubscriptionHistory extends Model
{
    /** @use HasFactory<SubscriptionHistoryFactory> */
    use HasFactory;

    protected $table = 'subscription_history';

    /**
     * Matches the original MySQL schema (created_at only).
     */
    public $timestamps = false;

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }

    protected function casts(): array
    {
        return [
            'previous_ends_at' => 'date',
            'new_ends_at' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
