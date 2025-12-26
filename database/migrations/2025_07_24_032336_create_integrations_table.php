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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type'); // 'webhook', 'hubspot', 'zoho', 'softchap'
            $table->string('name'); // 'Zapier', 'Make', 'HubSpot', etc.
            $table->text('config'); // JSON configuration (webhook URL, tokens, etc.)
            $table->json('events')->nullable(); // Array of enabled events
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('last_error_at')->nullable();
            $table->text('last_error_message')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type']);
            $table->index(['type', 'is_active']);
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create webhook logs table for debugging
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('integration_id');
            $table->string('event_type'); // 'card_tap', 'new_lead', 'card_created'
            $table->text('payload'); // JSON payload sent
            $table->integer('response_code')->nullable();
            $table->text('response_body')->nullable();
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['integration_id', 'created_at']);
            $table->index(['event_type', 'success']);
            
            // Foreign key
            $table->foreign('integration_id')->references('id')->on('integrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('integrations');
    }
};
