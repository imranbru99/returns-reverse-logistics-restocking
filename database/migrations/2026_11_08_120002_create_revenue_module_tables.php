<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_listings', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('grade')->nullable();
            $table->bigInteger('price_cents');
            $table->string('currency', 3)->default('USD');
            $table->json('photos')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('listed_at')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('marketplace_orders', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('marketplace_listing_id')->constrained('marketplace_listings')->cascadeOnDelete();
            $table->string('buyer_email');
            $table->string('buyer_name')->nullable();
            $table->bigInteger('total_cents');
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('liquidation_lots', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->bigInteger('reserve_price_cents')->default(0);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('auctions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('liquidation_lot_id')->constrained('liquidation_lots')->cascadeOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status')->default('scheduled');
            $table->bigInteger('winning_bid_cents')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('auction_bids', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('auction_id')->constrained('auctions')->cascadeOnDelete();
            $table->string('bidder_name');
            $table->string('bidder_email')->nullable();
            $table->bigInteger('amount_cents');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'auction_id']);
        });

        Schema::create('recycling_partners', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('contact_email')->nullable();
            $table->json('services')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('destruction_certificates', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('recycling_partner_id')->nullable()->constrained('recycling_partners')->nullOnDelete();
            $table->string('certificate_number');
            $table->timestamp('issued_at');
            $table->string('pdf_path')->nullable();
            $table->json('items')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['tenant_id', 'certificate_number']);
        });

        Schema::create('sustainability_metrics', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('co2_kg', 12, 3)->default(0);
            $table->decimal('waste_diverted_kg', 12, 3)->default(0);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sustainability_metrics');
        Schema::dropIfExists('destruction_certificates');
        Schema::dropIfExists('recycling_partners');
        Schema::dropIfExists('auction_bids');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('liquidation_lots');
        Schema::dropIfExists('marketplace_orders');
        Schema::dropIfExists('marketplace_listings');
    }
};
