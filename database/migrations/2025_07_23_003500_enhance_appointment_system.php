<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Add missing appointment settings to businesses table
        Schema::table('businesses', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('businesses', 'appointment_color')) {
                $table->string('appointment_color')->default('#007bff')->after('appointment_settings');
            }
            if (!Schema::hasColumn('businesses', 'appointment_duration')) {
                $table->integer('appointment_duration')->default(60)->after('appointment_color'); // in minutes
            }
            if (!Schema::hasColumn('businesses', 'business_hours_start')) {
                $table->time('business_hours_start')->default('09:00:00')->after('appointment_duration');
            }
            if (!Schema::hasColumn('businesses', 'business_hours_end')) {
                $table->time('business_hours_end')->default('17:00:00')->after('business_hours_start');
            }
            if (!Schema::hasColumn('businesses', 'appointment_days')) {
                $table->json('appointment_days')->nullable()->after('business_hours_end'); // days of week
            }
        });

        // Add enhanced fields to appointment_deatails table
        Schema::table('appointment_deatails', function (Blueprint $table) {
            if (!Schema::hasColumn('appointment_deatails', 'status')) {
                $table->string('status')->default('pending')->after('time'); // pending, confirmed, cancelled, completed
            }
            if (!Schema::hasColumn('appointment_deatails', 'note')) {
                $table->text('note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('appointment_deatails', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('note');
            }
            if (!Schema::hasColumn('appointment_deatails', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('appointment_deatails', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('appointment_deatails', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable()->after('cancelled_at');
            }
            if (!Schema::hasColumn('appointment_deatails', 'email_sent')) {
                $table->boolean('email_sent')->default(false)->after('cancellation_reason');
            }
            if (!Schema::hasColumn('appointment_deatails', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable()->after('email_sent');
            }
            if (!Schema::hasColumn('appointment_deatails', 'gmail_message_id')) {
                $table->string('gmail_message_id')->nullable()->after('email_sent_at');
            }
        });

        // Add appointment statistics table for admin dashboard
        if (!Schema::hasTable('appointment_statistics')) {
            Schema::create('appointment_statistics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('business_id')->nullable();
                $table->date('date');
                $table->integer('total_appointments')->default(0);
                $table->integer('confirmed_appointments')->default(0);
                $table->integer('pending_appointments')->default(0);
                $table->integer('cancelled_appointments')->default(0);
                $table->integer('completed_appointments')->default(0);
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
                $table->unique(['user_id', 'business_id', 'date']);
            });
        }
    }

    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'appointment_color', 
                'appointment_duration',
                'business_hours_start',
                'business_hours_end',
                'appointment_days'
            ]);
        });

        Schema::table('appointment_deatails', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'note', 
                'admin_note',
                'confirmed_at',
                'cancelled_at',
                'cancellation_reason',
                'email_sent',
                'email_sent_at',
                'gmail_message_id'
            ]);
        });

        Schema::dropIfExists('appointment_statistics');
    }
}; 