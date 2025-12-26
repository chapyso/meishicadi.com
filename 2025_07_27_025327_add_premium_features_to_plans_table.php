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
        Schema::table('plans', function (Blueprint $table) {
            $table->enum('enable_bulk_transfer', ['on', 'off'])->default('off')->after('enable_pro_color_wheel');
            $table->enum('enable_wallet', ['on', 'off'])->default('off')->after('enable_bulk_transfer');
            $table->enum('enable_integrations', ['on', 'off'])->default('off')->after('enable_wallet');
            $table->enum('enable_analytics', ['on', 'off'])->default('off')->after('enable_integrations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['enable_bulk_transfer', 'enable_wallet', 'enable_integrations', 'enable_analytics']);
        });
    }
};
