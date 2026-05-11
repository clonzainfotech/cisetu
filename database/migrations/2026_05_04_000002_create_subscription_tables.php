<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('code', 32)->unique();
                $table->string('name', 128);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->decimal('price_per_year_inr', 12, 2)->default(0);
                $table->char('currency', 3)->default('INR');
                $table->unsignedInteger('max_user_accounts')->nullable();
                $table->timestamps(3);
            });
        }

        if (! Schema::hasTable('village_subscriptions')) {
            Schema::create('village_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('village_id')->constrained()->cascadeOnDelete();
                $table->foreignId('plan_id')->constrained('subscription_plans');
                $table->string('status')->default('active');
                $table->date('starts_at');
                $table->date('ends_at');
                $table->date('grace_ends_at')->nullable();
                $table->string('billing_reference', 128)->nullable();
                $table->timestamps(3);

                $table->unique(['village_id']);
                $table->index(['ends_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('village_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
