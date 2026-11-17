<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type', 20);
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('reference_id');
            $table->string('campaign_title');
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'activity_type', 'activity_id']);
            $table->index(['activity_type', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
