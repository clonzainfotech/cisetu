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
            $table->string('logo')->nullable()->after('name_local');
            $table->string('upi_id')->nullable()->after('logo');
            $table->string('upi_name')->nullable()->after('upi_id');
            $table->string('payment_note')->nullable()->after('upi_name');
            $table->timestamp('subscription_expires_at')->nullable()->after('payment_note');
        });
    }

    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn(['logo', 'upi_id', 'upi_name', 'payment_note', 'subscription_expires_at']);
        });
    }
};
