<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id_certificate');
            $table->unsignedInteger('id_donation')->unique();
            $table->string('certificate_number', 50)->unique();
            $table->string('file_path', 255);
            $table->timestamp('issued_at')->useCurrent();

            $table->foreign('id_donation')->references('id_donation')->on('donations')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
