<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // 1. Change to string first to allow any value
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        // 2. Update existing data
        DB::table('campaigns')->where('status', 'active')->update(['status' => 'verified']);
        DB::table('campaigns')->whereNotIn('status', ['pending', 'verified', 'rejected'])->update(['status' => 'pending']);

        // 3. Change to new ENUM
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });
    }
};
