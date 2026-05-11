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
        Schema::table('donation_transactions', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->text('prayer')->nullable()->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_transactions', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'prayer']);
        });
    }
};
