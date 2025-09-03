<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_google_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // index en vez de unique para evitar problemas con múltiples nulls
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->index();
            }
            // opcional
            // if (!Schema::hasColumn('users', 'avatar_url')) {
            //     $table->string('avatar_url')->nullable();
            // }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id'/*, 'avatar_url'*/]);
        });
    }
};
