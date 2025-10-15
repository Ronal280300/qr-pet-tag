<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Evita romper si ya existen
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id', 191)->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }
            // Útil si tu flujo verifica email desde Google
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('avatar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id'))  $table->dropColumn('google_id');
            if (Schema::hasColumn('users', 'avatar'))     $table->dropColumn('avatar');
            // NO borro email_verified_at por ser estándar de Laravel, pero si quieres:
            // if (Schema::hasColumn('users', 'email_verified_at')) $table->dropColumn('email_verified_at');
        });
    }
};
