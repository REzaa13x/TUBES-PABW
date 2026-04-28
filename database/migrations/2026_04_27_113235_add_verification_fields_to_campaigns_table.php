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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('verification_token')->nullable()->unique()->after('status');
            $table->string('validator_name')->nullable()->after('verification_token');
            $table->timestamp('verified_at')->nullable()->after('validator_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'validator_name', 'verified_at']);
        });
    }
};
