<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id_donation');
            $table->unsignedInteger('id_campaign');
            $table->unsignedInteger('id_user');    
            $table->decimal('amount', 15, 2);   
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->dateTime('paid_at')->nullable(); 
            $table->timestamps();

            $table->index(['id_campaign', 'status']);
            $table->index(['id_user', 'status']);

            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_user')->references('id_user')->on('users')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE donations ADD CONSTRAINT chk_donations_amount CHECK (amount > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
