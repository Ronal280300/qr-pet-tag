<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pets')) {
            throw new RuntimeException('La tabla pets no existe.');
        }

        // MySQL/MariaDB: asumiendo users.id = BIGINT UNSIGNED (increments / bigIncrements)
        DB::statement('ALTER TABLE pets MODIFY user_id BIGINT UNSIGNED NULL;');
    }

    public function down(): void
    {
        // Revertir a NOT NULL (siempre que no existan filas con user_id NULL)
        DB::statement('ALTER TABLE pets MODIFY user_id BIGINT UNSIGNED NOT NULL;');
    }
};