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
        if (! Schema::hasTable('homes')) {
            Schema::create('homes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('village_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('property_no', 64);
                $table->string('house_no', 64)->nullable();
                $table->string('owner', 512)->nullable();
                $table->string('occupant', 512)->nullable();
                $table->text('address')->nullable();
                $table->decimal('total', 14, 2)->default(0);
                $table->timestamps();

                $table->unique(['village_id', 'property_no']);
            });
        }

        if (! Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('village_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('reg_no', 64);
                $table->string('name', 512);
                $table->decimal('total', 14, 2)->default(0);
                $table->timestamps();

                $table->unique(['village_id', 'reg_no']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
        Schema::dropIfExists('homes');
    }
};
