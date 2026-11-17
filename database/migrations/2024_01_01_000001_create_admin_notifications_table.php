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
        // MySQL may leave the table behind when adding a foreign key fails.
        // Preserve it (and any data) so a failed deploy can be retried.
        if (Schema::hasTable('admin_notifications')) {
            return;
        }

        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Deskripsi detail
            $table->string('category')->default('sistem'); // kampanye, donasi, relawan, laporan, smart_matching, sistem
            $table->string('priority')->default('medium'); // high, medium, low
            $table->string('icon')->nullable(); // Emoji atau nama icon
            $table->string('type')->nullable(); // campaign_new, donation_new, volunteer_new, report_new, matching_found, etc
            $table->string('action_label')->nullable(); // "Lihat Detail", "Setujui", etc
            $table->string('action_url')->nullable(); // URL untuk tombol aksi
            $table->string('secondary_action_label')->nullable(); // Tombol aksi kedua
            $table->string('secondary_action_url')->nullable();
            // These tables are created later. Keep notification references without
            // constraints, as required by the later foreign-key removal migration.
            $table->foreignId('related_campaign_id')->nullable();
            $table->foreignId('related_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('related_donation_id')->nullable();
            $table->foreignId('related_volunteer_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->json('metadata')->nullable(); // Extra data sebagai JSON
            $table->timestamps();
            
            // Indexes
            $table->index('category');
            $table->index('priority');
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
