<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Per-VCard (business) appointment settings
        Schema::table('businesses', function (Blueprint $table) {
            $table->json('appointment_settings')->nullable()->after('logo');
            $table->boolean('appointment_active')->default(true)->after('appointment_settings');
            $table->boolean('gmail_sync_enabled')->default(false)->after('appointment_active');
            $table->string('gmail_email')->nullable()->after('gmail_sync_enabled');
            $table->text('gmail_token')->nullable()->after('gmail_email');
        });
        // Super admin control for Gmail sync per user
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('allow_gmail_sync')->default(false)->after('email');
        });
    }
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['appointment_settings', 'appointment_active', 'gmail_sync_enabled', 'gmail_email', 'gmail_token']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['allow_gmail_sync']);
        });
    }
};
