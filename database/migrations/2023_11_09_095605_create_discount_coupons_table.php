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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            // Discount coupon code
            $table->string('code');
            // Human readable discount coupon code
            $table->string('name')->nullable();
            // Decription of coupon code - not necessary
            $table->text('description')->nullable();
            // max uses of discount coupon
            $table->integer('max_uses')->nullable();
            // How many times a user can use the discount coupon
            $table->integer('max_uses_user')->nullable();
            // Whether or not the discount coupon is a percentage or fixed price
            $table->enum('type', ['percentage', 'fixed'] )->default('fixed');
            // The amount to discount based on the type 
            $table->double('discount_amount', 10, 2);
            // The min amount compared with subtotal 
            $table->double('min_amount', 10, 2)->nullable();
            // Status
            $table->integer('status')->default(1);
            // when the coupon begins
            $table->timestamp('starts_at')->nullable();
            // when the coupon ends
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
