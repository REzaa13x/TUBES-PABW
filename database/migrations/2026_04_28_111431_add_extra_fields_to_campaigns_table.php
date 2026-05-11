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
            $table->string('lokasi')->nullable()->after('kategori');
            $table->string('jenis_penerima')->nullable()->after('yayasan');
            $table->string('whatsapp')->nullable()->after('penyaluran');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'jenis_penerima', 'whatsapp']);
        });
    }
};
