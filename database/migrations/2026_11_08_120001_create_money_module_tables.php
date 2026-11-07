<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_credit_accounts', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('currency', 3)->default('USD');
            $table->bigInteger('balance_cents')->default(0);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'customer_id', 'currency']);
        });

        Schema::create('store_credit_ledger_entries', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('store_credit_account_id')->constrained('store_credit_accounts')->cascadeOnDelete();
            $table->string('type');
            $table->bigInteger('amount_cents');
            $table->bigInteger('balance_after_cents');
            $table->string('reference_type')->nullable();
            $table->ulid('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'store_credit_account_id']);
        });

        Schema::create('gift_cards', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('code');
            $table->bigInteger('initial_balance_cents');
            $table->bigInteger('balance_cents');
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('exchange_orders', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_id')->constrained('rmas')->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->json('new_order_items');
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('fraud_signals', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignUlid('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignUlid('rma_id')->nullable()->constrained('rmas')->nullOnDelete();
            $table->unsignedSmallInteger('score')->default(0);
            $table->json('signals')->nullable();
            $table->string('status')->default('open');
            $table->timestamp('reviewed_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('warranty_registrations', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUlid('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('serial_number')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('warranty_claims', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warranty_registration_id')->constrained('warranty_registrations')->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('submitted');
            $table->text('description')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('return_cost_ledgers', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('rma_id')->constrained('rmas')->cascadeOnDelete();
            $table->bigInteger('shipping_cents')->default(0);
            $table->bigInteger('labor_cents')->default(0);
            $table->bigInteger('parts_cents')->default(0);
            $table->bigInteger('fees_cents')->default(0);
            $table->bigInteger('recovery_cents')->default(0);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'rma_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_cost_ledgers');
        Schema::dropIfExists('warranty_claims');
        Schema::dropIfExists('warranty_registrations');
        Schema::dropIfExists('fraud_signals');
        Schema::dropIfExists('exchange_orders');
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('store_credit_ledger_entries');
        Schema::dropIfExists('store_credit_accounts');
    }
};
