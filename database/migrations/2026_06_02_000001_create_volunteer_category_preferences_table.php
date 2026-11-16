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
        Schema::create('volunteer_category_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category'); // Pendidikan, Kesehatan, Sosial, Lingkungan, Bencana Alam, Keagamaan
            $table->boolean('is_preferred')->default(true); // User sengaja memilih kategori ini
            $table->boolean('auto_discovered')->default(false); // Kategori otomatis ditemukan dari riwayat
            $table->integer('engagement_score')->default(1); // Semakin tinggi semakin sering user terlibat
            $table->timestamps();
            
            // Unique constraint: user hanya bisa punya satu preference per kategori
            $table->unique(['user_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_category_preferences');
    }
};
