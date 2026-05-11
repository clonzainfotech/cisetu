<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('states')) {
            Schema::create('states', function (Blueprint $table) {
                $table->id();
                $table->string('code', 8)->unique();
                $table->string('name_en', 128);
                $table->string('name_local', 128)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps(3);
            });
        }

        if (! Schema::hasTable('districts')) {
            Schema::create('districts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->constrained()->cascadeOnDelete();
                $table->string('code', 16)->nullable();
                $table->string('name_en', 128);
                $table->string('name_local', 128)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps(3);

                $table->index(['state_id']);
            });
        }

        if (! Schema::hasTable('villages')) {
            Schema::create('villages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('district_id')->constrained()->cascadeOnDelete();
                $table->string('subdomain', 63)->unique();
                $table->string('custom_domain')->nullable()->unique();
                $table->string('name_en');
                $table->string('name_local')->nullable();
                $table->string('census_code', 32)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps(3);

                $table->index(['district_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('states');
    }
};
