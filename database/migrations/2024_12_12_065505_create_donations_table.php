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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->text('message')->nullable();
            $table->boolean('anonymous')->default(false);
            $table->decimal('tip_percentage', 5, 2)->nullable();
            $table->boolean('opt_out_tip')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
