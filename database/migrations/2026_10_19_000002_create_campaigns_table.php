<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id_campaign');
            $table->unsignedInteger('id_user');      // penggalang pemilik campaign
            $table->unsignedInteger('id_category');
            $table->string('title', 150);
            $table->text('description');
            $table->string('image', 255)->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->date('deadline');
            $table->enum('status', ['draft', 'menunggu', 'aktif', 'ditolak', 'selesai', 'nonaktif'])
                  ->default('draft');
            $table->text('rejection_note')->nullable(); // alasan penolakan dari admin
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'deadline']);

            $table->foreign('id_user')->references('id_user')->on('users')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_category')->references('id_category')->on('categories')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE campaigns ADD CONSTRAINT chk_campaigns_target CHECK (target_amount > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
