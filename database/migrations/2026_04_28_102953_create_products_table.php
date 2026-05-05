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

            // 🔗 link to article
            $table
                ->foreignId('article_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table
                ->foreignId('tenant_category_id')
                ->nullable()
                ->constrained('tenant_categories')
                ->nullOnDelete();

            // 🧠 snapshot (important)
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // 💰 main product price (optional fallback)
            $table->decimal('price', 10, 2)->default(0);

            // 📦 simple stock (optional aggregate)
            $table->integer('stock')->default(0);

            // 🧩 override system
            $table->json('overrides')->nullable();

            // 🧠 status
            $table->string('status')->default('active');

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
