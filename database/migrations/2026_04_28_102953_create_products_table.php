<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // SaaS isolation
            $table
                ->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table
                ->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('type')->default('main');
            // sub, main
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('barcode')->nullable();
            $table->string('image')->nullable();

            $table->string('status')->default('active');
            // active, inactive

            $table->json('attributes')->nullable();
            // flexible fields (size, color, etc.)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
