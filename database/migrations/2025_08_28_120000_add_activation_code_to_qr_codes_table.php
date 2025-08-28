<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_activation_code_to_qr_codes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->string('activation_code', 32)->unique()->after('pet_id');
            }
        });
    }
    public function down(): void {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->dropUnique('qr_codes_activation_code_unique');
                $table->dropColumn('activation_code');
            }
        });
    }
};
