<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->date('prev_date');
            $table->unsignedBigInteger('prev_reading');
            $table->date('curr_date');
            $table->unsignedBigInteger('curr_reading');
            $table->decimal('electricity_rate', 10, 2)->nullable();
            $table->decimal('electricity_amount', 10, 2)->nullable();
            $table->unsignedInteger('total_heads');
            $table->decimal('water_price', 10, 2);
            $table->decimal('water_amount', 10, 2);
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('internet_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
