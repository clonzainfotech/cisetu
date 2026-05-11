<?php

namespace App\Models;

use Database\Factories\SubscriptionPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'code',
    'name',
    'description',
    'is_active',
    'sort_order',
    'price_per_year_inr',
    'currency',
    'max_user_accounts',
    'max_properties',
    'max_bots',
    'theme_customization',
    'import_export',
    'report_export',
    'dedicated_support',
    'advanced_reports_analytics',
    'message_report_system',
    'allows_custom_domain',
    'allows_dedicated_server',
    'payment_qr_mode',
])]
class SubscriptionPlan extends Model
{
    /** @use HasFactory<SubscriptionPlanFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'price_per_year_inr' => 'decimal:2',
            'max_user_accounts' => 'integer',
            'max_properties' => 'integer',
            'max_bots' => 'integer',
            'theme_customization' => 'boolean',
            'import_export' => 'boolean',
            'report_export' => 'boolean',
            'dedicated_support' => 'boolean',
            'advanced_reports_analytics' => 'boolean',
            'message_report_system' => 'boolean',
            'allows_custom_domain' => 'boolean',
            'allows_dedicated_server' => 'boolean',
        ];
    }
}
