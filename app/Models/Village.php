<?php

namespace App\Models;

use Database\Factories\VillageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'district_id',
    'subdomain',
    'custom_domain',
    'name_en',
    'name_local',
    'logo',
    'upi_id',
    'upi_name',
    'payment_note',
    'whatsapp_number',
    'subscription_plan_id',
    'subscription_start_at',
    'subscription_expires_at',
    'subscription_grace_ends_at',
    'subscription_status',
    'subscription_billing_reference',
    'census_code',
    'is_active',
    'portal_template',
    'password_length',
    'api_token',
])]
class Village extends Model
{
    /** @use HasFactory<VillageFactory> */
    use HasFactory;

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function homes(): HasMany
    {
        return $this->hasMany(Home::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function subscriptionHistories(): HasMany
    {
        return $this->hasMany(SubscriptionHistory::class);
    }

    public function maxUserAccounts(): ?int
    {
        if ($this->plan) {
            return $this->plan->max_user_accounts;
        }

        return null;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'subscription_plan_id' => 'integer',
            'subscription_start_at' => 'datetime',
            'subscription_expires_at' => 'datetime',
            'subscription_grace_ends_at' => 'datetime',
            'password_length' => 'integer',
        ];
    }

    public function isSubscriptionActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->subscription_status === 'suspended') {
            return false;
        }

        if ($this->subscription_status === 'expired') {
            return false;
        }

        if ($this->subscription_expires_at && $this->subscription_expires_at->isPast()) {
            // Check grace period
            if ($this->subscription_grace_ends_at && $this->subscription_grace_ends_at->isFuture()) {
                return true; // Still in grace
            }

            return false;
        }

        return true;
    }

    public function isSubscriptionInGrace(): bool
    {
        if (! $this->is_active || $this->subscription_status === 'suspended' || $this->subscription_status === 'expired') {
            return false;
        }

        return $this->subscription_expires_at &&
            $this->subscription_expires_at->isPast() &&
            $this->subscription_grace_ends_at &&
            $this->subscription_grace_ends_at->isFuture();
    }

    public function isSubscriptionExpired(): bool
    {
        if ($this->subscription_status === 'expired') {
            return true;
        }

        if ($this->subscription_expires_at && $this->subscription_expires_at->isPast()) {
            if ($this->subscription_grace_ends_at && $this->subscription_grace_ends_at->isFuture()) {
                return false; // Still in grace
            }

            return true;
        }

        return false;
    }
}
