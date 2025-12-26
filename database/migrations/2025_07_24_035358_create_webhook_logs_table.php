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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->onDelete('cascade');
            $table->string('event_type'); // card_tap, new_lead, card_created, etc.
            $table->text('payload'); // JSON payload sent
            $table->integer('response_code')->nullable(); // HTTP response code
            $table->text('response_body')->nullable(); // Response body
            $table->string('status'); // success, failed, pending
            $table->text('error_message')->nullable(); // Error message if failed
            $table->integer('attempt_count')->default(1); // Number of attempts
            $table->timestamp('sent_at')->nullable(); // When webhook was sent
            $table->timestamp('next_retry_at')->nullable(); // Next retry time
            $table->timestamps();
            
            $table->index(['integration_id', 'status']);
            $table->index(['event_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
