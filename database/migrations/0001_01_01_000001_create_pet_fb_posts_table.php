<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pet_fb_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();

            $table->string('status', 20)->default('queued'); // queued|processing|success|failed
            $table->string('post_id', 120)->nullable();
            $table->text('message')->nullable();

            // Dedupe
            $table->string('fingerprint', 64)->index()->nullable();

            // Para depurar
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->text('error_message')->nullable();

            // Para saber qué imagen usamos
            $table->string('image_kind', 10)->nullable(); // file|url
            $table->text('image_ref')->nullable(); // path absoluto o URL

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_fb_posts');
    }
};
