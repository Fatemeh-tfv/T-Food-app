<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('discount', 8, 2)->default(0);
        $table->decimal('shipping_fee', 8, 2)->default(0);
        $table->enum('payment_method', ['cash', 'card', 'paypal', 'other'])->nullable();
        $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
        $table->string('tracking_number')->nullable();
        $table->text('notes')->nullable();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['discount', 'shipping_fee', 'payment_method', 'payment_status', 'tracking_number', 'notes']);
    });
}

};
