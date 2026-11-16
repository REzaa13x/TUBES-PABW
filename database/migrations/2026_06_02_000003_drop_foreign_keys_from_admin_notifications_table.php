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
        Schema::table('admin_notifications', function (Blueprint $table) {
            // Drop foreign keys
            try {
                $table->dropForeign(['related_donation_id']);
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }

            try {
                $table->dropForeign(['related_volunteer_id']);
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }

            try {
                $table->dropForeign(['related_campaign_id']);
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            try {
                $table->foreign('related_donation_id')
                    ->references('id')
                    ->on('donations')
                    ->onDelete('cascade');
            } catch (\Exception $e) {}

            try {
                $table->foreign('related_volunteer_id')
                    ->references('id')
                    ->on('volunteers')
                    ->onDelete('cascade');
            } catch (\Exception $e) {}

            try {
                $table->foreign('related_campaign_id')
                    ->references('id')
                    ->on('campaigns')
                    ->onDelete('cascade');
            } catch (\Exception $e) {}
        });
    }
};
