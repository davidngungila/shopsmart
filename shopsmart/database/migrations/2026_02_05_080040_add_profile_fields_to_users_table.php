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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'language')) {
                $table->string('language')->default('en')->after('bio');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('UTC')->after('language');
            }
            if (!Schema::hasColumn('users', 'date_format')) {
                $table->string('date_format')->default('Y-m-d')->after('timezone');
            }
            if (!Schema::hasColumn('users', 'notifications_email')) {
                $table->boolean('notifications_email')->default(true)->after('date_format');
            }
            if (!Schema::hasColumn('users', 'notifications_sms')) {
                $table->boolean('notifications_sms')->default(false)->after('notifications_email');
            }
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->after('notifications_sms');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'avatar', 'bio', 'language', 'timezone', 'date_format', 'notifications_email', 'notifications_sms', 'theme']);
        });
    }
};
