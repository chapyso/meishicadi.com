<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('customcss')->nullable()->after('customjs');
            $table->string('google_fonts')->nullable()->after('customjs');
            $table->string('password')->nullable()->after('title');
            $table->string('enable_password')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
           $table->dropColumn('customcss');
           $table->dropColumn('google_fonts');
           $table->dropColumn('password');
        });
    }
}
