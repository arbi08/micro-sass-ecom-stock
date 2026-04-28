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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // SaaS isolation (مهم إلا بغيت addresses per tenant)
            $table
                ->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // ممكن ترتبط بـ user
            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('full_name');

            $table->string('phone');

            $table->string('city');

            $table->string('address_line');

            $table->string('postal_code')->nullable();

            $table->boolean('is_default')->default(false);

            $table->string('type')->default('delivery');
            // delivery, billing

            $table->string('label')->nullable();
            // home, office, warehouse

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
