<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToPlanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_requests', 'notes')) {
                $table->text('notes')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('plan_requests', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('notes');
            }
            if (!Schema::hasColumn('plan_requests', 'request_date')) {
                $table->timestamp('request_date')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_requests', function (Blueprint $table) {
            $table->dropColumn(['notes', 'status', 'request_date']);
        });
    }
}
