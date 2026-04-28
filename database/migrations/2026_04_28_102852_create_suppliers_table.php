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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // SaaS isolation
            $table
                ->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();  // SUP-001

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('city')->nullable();
            $table->string('address')->nullable();

            $table->string('tax_number')->nullable();  // مهم للشركات

            $table->string('status')->default('active');
            // active, inactive

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
