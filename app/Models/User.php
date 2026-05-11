<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\HasTeams;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'current_team_id', 'is_super_master_admin', 'role', 'village_id', 'permissions'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasTeams, Notifiable, TwoFactorAuthenticatable;

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function isSuperMasterAdmin(): bool
    {
        return $this->is_super_master_admin || $this->role === 'super_master_admin';
    }

    public function isVillageAdmin(): bool
    {
        return $this->role === 'village_admin' && $this->village_id !== null;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isVillageAdmin() || $this->isSuperMasterAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_super_master_admin' => 'boolean',
            'permissions' => 'array',
        ];
    }
}
