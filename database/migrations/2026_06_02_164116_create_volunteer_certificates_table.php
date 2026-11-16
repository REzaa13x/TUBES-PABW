<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('certificate_id')->unique(); // UUID unik
            $table->string('certificate_number')->unique(); // DGV-VOL-2026-0001

            // Relawan
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->string('volunteer_name');

            // Campaign
            $table->foreignId('campaign_id')->constrained('volunteer_campaigns')->onDelete('cascade');
            $table->string('campaign_name');
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Relasi ke lamaran
            $table->foreignId('application_id')->nullable()->constrained('volunteer_applications')->onDelete('set null');

            // Info sertifikat
            $table->date('issue_date');
            $table->string('volunteer_level')->default('Relawan Baru'); // Relawan Baru / Aktif / Senior / Ahli
            $table->integer('total_volunteer_hours')->default(0);
            $table->string('organizer_name')->default('Dongiv');

            // Status
            $table->enum('status', ['pending', 'active', 'revoked'])->default('pending');
            // pending = dibuat saat approved, belum bisa diunduh karena kampanye belum selesai
            // active  = kampanye selesai, bisa diunduh
            // revoked = dicabut admin

            // File & QR
            $table->string('certificate_file')->nullable(); // path PDF jika sudah digenerate
            $table->text('qr_code')->nullable(); // URL QR code (Google Chart API)

            // Metadata tambahan
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_certificates');
    }
};
