<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pet_photos', function (Blueprint $table) {
            if (!Schema::hasColumn('pet_photos', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('path');
                $table->index(['pet_id', 'sort_order']);
            }
        });

        // Si tu tabla antigua tenía la columna `order`, copia sus valores a sort_order
        if (Schema::hasColumn('pet_photos', 'order') && Schema::hasColumn('pet_photos', 'sort_order')) {
            DB::statement('UPDATE pet_photos SET sort_order = `order` WHERE sort_order = 0 OR sort_order IS NULL');
        }
    }

    public function down(): void
    {
        Schema::table('pet_photos', function (Blueprint $table) {
            if (Schema::hasColumn('pet_photos', 'sort_order')) {
                $table->dropIndex(['pet_id', 'sort_order']);
                $table->dropColumn('sort_order');
            }
        });
    }
};
