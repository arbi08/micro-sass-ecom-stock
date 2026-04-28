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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();  // مهم فـ SaaS (subdomain أو identifier)

            $table
                ->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->string('status')->default('active');
            // active, suspended

            $table->string('plan')->nullable();  // basic, pro... (اختياري)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
