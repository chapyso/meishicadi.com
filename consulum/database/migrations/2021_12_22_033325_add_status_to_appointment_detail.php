<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAppointmentDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'appointment_deatails', function (Blueprint $table){
            $table->string('status')->default('pending')->after('time');
            $table->text('note')->nullable()->after('status');
        }
        );
        Schema::table(
            config('visitor.table_name'), function (Blueprint $table){
            $table->integer('created_by')->nullable()->after('visitor_id');
        }
        );
        Schema::table(
            'plans', function (Blueprint $table){
            $table->string('enable_custdomain')->default('off')->after('description');
            $table->string('enable_custsubdomain')->default('off')->after('enable_custdomain');
        }
        );
        Schema::table(
            'businesses', function (Blueprint $table){
            $table->string('enable_businesslink')->nullable()->after('meta_description');
            $table->string('enable_subdomain')->nullable()->after('enable_businesslink');
            $table->string('subdomain')->nullable()->after('enable_subdomain');
            $table->string('enable_domain')->default('off')->after('subdomain');
            $table->string('domains')->nullable()->after('enable_domain');
        }
        );




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'appointment_deatails', function (Blueprint $table){
            $table->dropColumn('status');
            $table->dropColumn('note');
        }
        );

        Schema::table(
            config('visitor.table_name'), function (Blueprint $table){
            $table->dropColumn('created_by');
        }
        );
        Schema::table(
            'plans', function (Blueprint $table){
            $table->dropColumn('enable_custdomain');
            $table->dropColumn('enable_custsubdomain');
        }
        );
        Schema::table(
            'businesses', function (Blueprint $table){
            $table->dropColumn('enable_businesslink');
            $table->dropColumn('enable_subdomain');
            $table->dropColumn('subdomain');
            $table->dropColumn('enable_domain');
            $table->dropColumn('domains');
        }
        );
    }
}
