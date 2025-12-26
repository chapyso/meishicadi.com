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
        Schema::create('wallet_passes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');
            $table->string('pass_type'); // 'apple' or 'google'
            $table->string('pass_file_path')->nullable(); // Path to .pkpass file
            $table->text('google_wallet_data')->nullable(); // JSON data for Google Wallet
            $table->string('email');
            $table->boolean('email_sent')->default(false);
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamps();
            
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Rate limiting: one pass per business per 30 minutes
            $table->unique(['business_id', 'pass_type'], 'unique_business_pass_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_passes');
    }
};
