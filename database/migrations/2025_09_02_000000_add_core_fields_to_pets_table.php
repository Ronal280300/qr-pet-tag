<?php
// database/migrations/2025_09_02_000000_add_core_fields_to_pets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            // valores cortos y opcionales para no romper datos existentes
            $table->string('species', 20)->nullable()->after('breed');       // dog, cat, other
            $table->string('sex', 10)->nullable()->after('species');         // male, female, unknown
            $table->string('size', 10)->nullable()->after('sex');            // small, medium, large
            $table->string('color', 80)->nullable()->after('size');          // “Blanco con manchas…”
            $table->boolean('is_neutered')->nullable()->after('color');      // esterilizado/a
            $table->boolean('rabies_vaccine')->nullable()->after('is_neutered'); // antirrábica al día
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['species','sex','size','color','is_neutered','rabies_vaccine']);
        });
    }
};
