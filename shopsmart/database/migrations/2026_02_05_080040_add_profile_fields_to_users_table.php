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
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('language')->default('en')->after('bio');
            $table->string('timezone')->default('UTC')->after('language');
            $table->string('date_format')->default('Y-m-d')->after('timezone');
            $table->boolean('notifications_email')->default(true)->after('date_format');
            $table->boolean('notifications_sms')->default(false)->after('notifications_email');
            $table->string('theme')->default('light')->after('notifications_sms');
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
