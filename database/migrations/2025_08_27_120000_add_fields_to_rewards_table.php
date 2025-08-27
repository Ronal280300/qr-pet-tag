<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rewards')) {
            throw new RuntimeException('La tabla rewards no existe. Revisa migraciones.');
        }

        Schema::table('rewards', function (Blueprint $table) {
            // Si no existen, las creamos. Orden sugerido: después de pet_id.
            if (! Schema::hasColumn('rewards', 'active')) {
                $table->boolean('active')->default(false)->after('pet_id');
            }
            if (! Schema::hasColumn('rewards', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('active');
            }
            if (! Schema::hasColumn('rewards', 'message')) {
                $table->string('message', 200)->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            if (Schema::hasColumn('rewards', 'message')) {
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('rewards', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('rewards', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};