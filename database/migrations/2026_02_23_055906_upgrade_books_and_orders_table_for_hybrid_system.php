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
        Schema::table('books', function (Blueprint $table) {
            $table->enum('product_type', ['digital', 'physical', 'both'])->default('digital')->after('category_id');
            $table->integer('stock_quantity')->nullable()->after('product_type');
            $table->string('weight')->nullable()->after('stock_quantity');
            $table->string('sku')->nullable()->after('weight');
            $table->decimal('shipping_charge', 10, 2)->default(0)->after('sku');
            $table->decimal('format_price_pdf', 10, 2)->nullable()->after('shipping_charge');
            $table->decimal('format_price_hardcopy', 10, 2)->nullable()->after('format_price_pdf');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('selected_format')->nullable()->after('book_id'); // pdf, hardcopy
            $table->integer('quantity')->default(1)->after('selected_format');
            $table->decimal('shipping_charge', 10, 2)->default(0)->after('amount');
            $table->string('payment_method')->nullable()->after('shipping_charge'); // online, cod
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid')->after('payment_method');
            $table->enum('order_status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('payment_status');

            // Shipping Info
            $table->string('shipping_name')->nullable()->after('order_status');
            $table->string('shipping_phone')->nullable()->after('shipping_name');
            $table->text('shipping_address')->nullable()->after('shipping_phone');
            $table->string('shipping_district')->nullable()->after('shipping_address');
            $table->string('shipping_postcode')->nullable()->after('shipping_district');
            $table->text('delivery_note')->nullable()->after('shipping_postcode');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'stock_quantity', 'weight', 'sku', 'shipping_charge', 'format_price_pdf', 'format_price_hardcopy']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['selected_format', 'quantity', 'shipping_charge', 'payment_method', 'payment_status', 'order_status', 'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_district', 'shipping_postcode', 'delivery_note']);
        });
    }
};
