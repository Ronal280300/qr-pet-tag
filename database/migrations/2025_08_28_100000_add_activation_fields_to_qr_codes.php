<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->string('activation_code', 50)->unique()->after('image');
            }
            if (!Schema::hasColumn('qr_codes', 'is_activated')) {
                $table->boolean('is_activated')->default(false)->after('activation_code');
            }
            if (!Schema::hasColumn('qr_codes', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('is_activated');
            }
            if (!Schema::hasColumn('qr_codes', 'activated_by')) {
                $table->foreignId('activated_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete()
                    ->after('activated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (Schema::hasColumn('qr_codes', 'activated_by')) {
                $table->dropConstrainedForeignId('activated_by');
            }
            if (Schema::hasColumn('qr_codes', 'activated_at')) {
                $table->dropColumn('activated_at');
            }
            if (Schema::hasColumn('qr_codes', 'is_activated')) {
                $table->dropColumn('is_activated');
            }
            if (Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->dropColumn('activation_code');
            }
        });
    }
};