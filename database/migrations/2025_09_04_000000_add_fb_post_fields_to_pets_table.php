<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('last_fb_post_id', 100)->nullable()->after('updated_at');
            $table->string('last_fb_page_id', 50)->nullable()->after('last_fb_post_id');
            $table->timestamp('last_fb_posted_at')->nullable()->after('last_fb_page_id');
            $table->string('last_fb_post_hash', 64)->nullable()->after('last_fb_posted_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['last_fb_post_id', 'last_fb_page_id', 'last_fb_posted_at', 'last_fb_post_hash']);
        });
    }
};
