<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->string('card_id')->nullable(); // For multiple cards per business
            $table->string('tap_source'); // QR, NFC, Link, Direct
            $table->string('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('device_type'); // mobile, tablet, desktop
            $table->string('device_os')->nullable(); // iOS, Android, Windows, macOS, Linux
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->text('suspicious_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            
            $table->index(['user_id', 'business_id']);
            $table->index(['created_at']);
            $table->index(['tap_source']);
            $table->index(['device_type']);
            $table->index(['country']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tap_analytics');
    }
} 