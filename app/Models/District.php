<?php

namespace App\Models;

use Database\Factories\DistrictFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['state_id', 'code', 'name_en', 'name_local', 'is_active'])]
class District extends Model
{
    /** @use HasFactory<DistrictFactory> */
    use HasFactory;

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
