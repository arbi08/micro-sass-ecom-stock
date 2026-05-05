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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // 🔗 relation
            $table
                ->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table
                ->foreignId('article_variant_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // 🧩 variant data (snapshot)
            $table->json('attributes');
            // example: { "color": "black", "size": "M" }

            // 💰 real business data
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);

            // 🖼️ optional override image
            $table->string('image')->nullable();

            // 🧠 override system
            $table->json('overrides')->nullable();

            // 🔢 SKU per vendor
            $table->string('sku')->nullable();

            // ⚡ status
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
