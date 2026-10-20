<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id_payment');
            $table->unsignedInteger('id_donation');
            $table->string('order_id', 50)->unique(); 
            $table->string('transaction_id', 100)->nullable(); 
            $table->string('method', 50)->nullable();
            $table->string('gateway_status', 30)->nullable();
            $table->timestamps();

            $table->foreign('id_donation')->references('id_donation')->on('donations')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
