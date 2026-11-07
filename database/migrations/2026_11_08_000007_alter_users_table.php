<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->ulid('ulid')->nullable()->unique()->after('id');
            $table->string('user_type')->nullable()->after('email');
            $table->string('phone')->nullable()->after('user_type');
            $table->string('locale', 10)->nullable()->after('phone');
            $table->string('timezone')->nullable()->after('locale');
            $table->boolean('is_platform_admin')->default(false)->after('timezone');
            $table->text('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->json('preferences')->nullable()->after('last_login_ip');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'ulid',
                'user_type',
                'phone',
                'locale',
                'timezone',
                'is_platform_admin',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'last_login_at',
                'last_login_ip',
                'preferences',
            ]);
        });
    }
};
