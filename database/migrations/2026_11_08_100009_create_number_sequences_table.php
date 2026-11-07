<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('number_sequences', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('sequence');
            $table->string('prefix')->nullable();
            $table->unsignedBigInteger('last_number')->default(0);
            $table->unsignedTinyInteger('padding')->default(6);
            $table->timestamps();

            $table->unique(['tenant_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
    }
};
