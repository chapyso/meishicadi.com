<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletPassesTable extends Migration
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
            $table->string('wallet_type'); // 'apple' or 'google'
            $table->string('pass_id')->unique(); // Unique identifier for the pass
            $table->string('serial_number')->unique(); // Serial number for the pass
            $table->string('status')->default('active'); // active, expired, revoked
            $table->text('pass_data')->nullable(); // JSON data for the pass
            $table->string('file_path')->nullable(); // Path to .pkpass file for Apple
            $table->string('google_wallet_object_id')->nullable(); // Google Wallet Object ID
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['business_id', 'wallet_type']);
            $table->index(['user_id', 'wallet_type']);
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
} 