<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 32)->default('user')->after('password');
            });
        }

        if (! Schema::hasColumn('users', 'village_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('village_id')->nullable()->after('role')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'village_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('village_id');
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
