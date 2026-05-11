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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // demo, subscription
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('village_name');
            $table->string('district_name');
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans');
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending, contacted, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
