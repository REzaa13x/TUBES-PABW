<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 11, 7)->nullable();
            $table->text('recipient_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', fn (Blueprint $table) => $table->dropColumn(['latitude', 'longitude', 'recipient_description']));
    }
};
