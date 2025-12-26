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
        Schema::create('bulk_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->bigInteger('file_size'); // in bytes
            $table->string('file_type');
            $table->string('transfer_token')->unique();
            $table->string('password')->nullable(); // hashed password if protection enabled
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'deleted'])->default('active');
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['transfer_token']);
            $table->index(['expires_at']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_transfers');
    }
};
