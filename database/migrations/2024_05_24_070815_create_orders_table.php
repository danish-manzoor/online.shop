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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal', 10, 2);
            $table->double('grand_total', 10, 2);
            $table->double('shipping', 10, 2);
            $table->double('discount', 10, 2)->nullable();
            $table->string('coupon_code', 10, 2)->nullable();
            $table->integer('coupon_code_id')->nullable();
            $table->enum('status', ['delivered', 'pending', 'shipped', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['paid', 'not paid'])->default('not paid');
            $table->integer('shipped_date')->nullable();
            //Customer address information
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->string('mobile');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('apartment')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
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
