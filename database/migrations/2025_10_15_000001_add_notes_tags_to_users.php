<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable()->after('avatar_original');
            }
            if (!Schema::hasColumn('users', 'tags')) {
                $table->json('tags')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tags')) {
                $table->dropColumn('tags');
            }
            if (Schema::hasColumn('users', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
