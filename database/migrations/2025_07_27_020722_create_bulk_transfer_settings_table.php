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
        Schema::create('bulk_transfer_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->nullable(); // null for global settings
            $table->boolean('feature_enabled')->default(true);
            $table->integer('max_file_size_mb')->default(2048); // 2GB default
            $table->integer('retention_hours')->default(72); // 72 hours default
            $table->boolean('password_protection_enabled')->default(true);
            $table->integer('daily_transfer_limit')->default(10);
            $table->integer('monthly_transfer_limit')->default(100);
            $table->bigInteger('max_storage_gb')->default(10); // 10GB default
            $table->timestamps();
            
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->unique('plan_id'); // one setting per plan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_transfer_settings');
    }
};
