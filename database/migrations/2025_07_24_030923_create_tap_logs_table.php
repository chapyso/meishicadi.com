<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('ip_address', 45)->nullable(); // IPv6 support
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, desktop, tablet
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable();
            $table->string('referrer')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->enum('tap_type', ['direct', 'qr_scan', 'share_link', 'nfc'])->default('direct');
            $table->string('session_id', 100)->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['business_id', 'created_at']);
            $table->index(['country', 'created_at']);
            $table->index(['tap_type', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            
            // Foreign key constraint
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tap_logs');
    }
};
