<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->boolean('has_emergency_contact')->default(false)->after('rabies_vaccine');
            $table->string('emergency_contact_name')->nullable()->after('has_emergency_contact');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['has_emergency_contact', 'emergency_contact_name', 'emergency_contact_phone']);
        });
    }
};
