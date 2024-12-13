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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('purpose')->nullable();
            $table->string('used_in')->nullable();
            $table->text('description')->nullable();
            $table->string('feature_image')->nullable();
            $table->json('gallary_images')->nullable();
            $table->string('video_url')->nullable();
            $table->dateTime('starting_date')->nullable();
            $table->dateTime('ending_date')->nullable();
            $table->string('funding_goal')->nullable();
            $table->string('location')->nullable();
            $table->boolean('term_and_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
