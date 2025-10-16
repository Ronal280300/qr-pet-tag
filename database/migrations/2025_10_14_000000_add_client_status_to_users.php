<?php
// database/migrations/2025_10_14_000000_add_client_status_to_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('is_admin'); // active|pending|inactive
            $table->timestamp('pending_since')->nullable()->after('status');
            $table->timestamp('status_changed_at')->nullable()->after('pending_since');
        });

        // Inicializa status para existentes
        DB::table('users')->update([
            'status' => 'active',
            'status_changed_at' => now(),
        ]);
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status','pending_since','status_changed_at']);
        });
    }
};
