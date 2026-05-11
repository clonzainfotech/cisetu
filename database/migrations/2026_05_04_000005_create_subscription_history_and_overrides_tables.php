<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subscription_history')) {
            Schema::create('subscription_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('village_id')->constrained()->cascadeOnDelete();
                $table->foreignId('plan_id')->constrained('subscription_plans');
                $table->string('event_type', 32);
                $table->date('previous_ends_at')->nullable();
                $table->date('new_ends_at')->nullable();
                $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('note', 512)->nullable();

                // Align with CIS schema (`created_at` only).
                $table->timestamp('created_at', 3)->nullable();

                $table->index(['village_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('village_subscription_overrides')) {
            Schema::create('village_subscription_overrides', function (Blueprint $table) {
                $table->id();
                $table->foreignId('village_id')->constrained()->cascadeOnDelete()->unique();
                $table->unsignedInteger('max_properties_override')->nullable();
                $table->unsignedInteger('max_user_accounts_override')->nullable();
                $table->unsignedInteger('max_bots_override')->nullable();
                $table->date('expires_at')->nullable();
                $table->string('note', 512)->nullable();

                // Align with CIS schema (`created_at` only).
                $table->timestamp('created_at', 3)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('village_subscription_overrides');
        Schema::dropIfExists('subscription_history');
    }
};
