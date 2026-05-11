<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->timestamp('subscription_grace_ends_at')->nullable()->after('subscription_expires_at');
            $table->string('subscription_status')->default('active')->after('subscription_grace_ends_at');
            $table->string('subscription_billing_reference')->nullable()->after('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn(['subscription_grace_ends_at', 'subscription_status', 'subscription_billing_reference']);
        });
    }
};
