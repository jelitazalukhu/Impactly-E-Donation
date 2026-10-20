<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_distributions', function (Blueprint $table) {
            $table->increments('id_distribution');
            $table->unsignedInteger('id_campaign')->index();
            $table->decimal('amount', 15, 2);
            $table->string('recipient', 150);  
            $table->text('description');
            $table->string('proof_file', 255)->nullable();
            $table->date('distributed_at');
            $table->boolean('is_active')->default(true); 
            $table->timestamps();

            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE fund_distributions ADD CONSTRAINT chk_distributions_amount CHECK (amount > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_distributions');
    }
};
