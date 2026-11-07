<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('address')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('warehouse_zones', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('type')->default('storage');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['warehouse_id', 'code']);
        });

        Schema::create('warehouse_aisles', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_zone_id')->constrained('warehouse_zones')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['warehouse_zone_id', 'code']);
        });

        Schema::create('warehouse_bins', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_aisle_id')->constrained('warehouse_aisles')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['warehouse_aisle_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_bins');
        Schema::dropIfExists('warehouse_aisles');
        Schema::dropIfExists('warehouse_zones');
        Schema::dropIfExists('warehouses');
    }
};
