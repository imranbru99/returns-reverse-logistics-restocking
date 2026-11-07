<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('domain')->nullable()->unique();
            $table->string('subdomain')->nullable()->unique();
            $table->string('business_preset')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('locale', 10)->default('en');
            $table->string('currency', 3)->default('USD');
            $table->string('country_code', 2)->nullable();
            $table->json('branding')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->string('demo_key')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('subdomain');
            $table->index('domain');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
