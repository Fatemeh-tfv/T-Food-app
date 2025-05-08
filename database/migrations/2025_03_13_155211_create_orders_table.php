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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 8, 2);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'paypal', 'other'])->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'delivered'])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->string('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->text('notes')->nullable();
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
