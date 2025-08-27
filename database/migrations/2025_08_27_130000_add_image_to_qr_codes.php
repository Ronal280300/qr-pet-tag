<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('qr_codes', 'image')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->string('image', 255)->nullable()->after('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('qr_codes', 'image')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
}; 