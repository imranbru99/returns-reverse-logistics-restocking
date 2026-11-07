<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_checklists', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->foreignUlid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->json('steps')->nullable();
            $table->json('required_evidence')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('carrier_accounts', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('driver');
            $table->string('name');
            $table->text('credentials')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('sandbox')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'driver']);
        });

        Schema::create('shipments', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_id')->nullable()->constrained('rmas')->nullOnDelete();
            $table->string('direction');
            $table->string('carrier');
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('draft');
            $table->string('label_path')->nullable();
            $table->unsignedInteger('rate_cents')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'tracking_number']);
        });

        Schema::create('receiving_records', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_id')->nullable()->constrained('rmas')->nullOnDelete();
            $table->foreignUlid('shipment_id')->nullable()->constrained('shipments')->nullOnDelete();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('expected_qty')->default(0);
            $table->unsignedInteger('received_qty')->default(0);
            $table->boolean('damaged_in_transit')->default(false);
            $table->boolean('orphan')->default(false);
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'warehouse_id']);
        });

        Schema::create('putaway_tasks', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->foreignUlid('from_bin_id')->nullable()->constrained('warehouse_bins')->nullOnDelete();
            $table->foreignUlid('to_bin_id')->constrained('warehouse_bins')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->foreignUlid('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('refurbishment_work_orders', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_item_id')->constrained('rma_items')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->unsignedInteger('parts_cost_cents')->default(0);
            $table->unsignedInteger('labor_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('vendor_claims', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_item_id')->constrained('rma_items')->cascadeOnDelete();
            $table->string('supplier_name');
            $table->string('status')->default('draft');
            $table->unsignedInteger('claim_amount_cents')->default(0);
            $table->json('evidence')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_claims');
        Schema::dropIfExists('refurbishment_work_orders');
        Schema::dropIfExists('putaway_tasks');
        Schema::dropIfExists('receiving_records');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('carrier_accounts');
        Schema::dropIfExists('inspection_checklists');
    }
};
