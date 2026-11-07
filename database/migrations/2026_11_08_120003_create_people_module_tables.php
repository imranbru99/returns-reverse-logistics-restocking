<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('job_title')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'user_id']);
        });

        Schema::create('shifts', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('staff_profile_id')->constrained('staff_profiles')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status')->default('scheduled');
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('work_tasks', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('queue');
            $table->unsignedTinyInteger('priority')->default(3);
            $table->timestamp('sla_due_at')->nullable();
            $table->string('status')->default('pending');
            $table->foreignUlid('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUlid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('title');
            $table->nullableUlidMorphs('subject');
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'queue', 'status']);
        });

        Schema::create('support_tickets', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_id')->nullable()->constrained('rmas')->nullOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('number');
            $table->string('subject');
            $table->string('status')->default('open');
            $table->unsignedTinyInteger('csat_score')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('knowledge_articles', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('locale', 8)->default('en');
            $table->longText('body');
            $table->boolean('is_public')->default(false);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('work_tasks');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('staff_profiles');
    }
};
