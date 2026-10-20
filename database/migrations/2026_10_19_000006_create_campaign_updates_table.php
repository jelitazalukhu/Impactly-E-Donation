<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->increments('id_campaign_update');
            $table->unsignedInteger('id_campaign')->index();
            $table->string('title', 150);
            $table->text('content');
            $table->string('image', 255)->nullable();
            $table->boolean('is_active')->default(true); 
            $table->timestamps();

            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_updates');
    }
};
