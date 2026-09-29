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
        if (!Schema::hasTable('pv_access_right_position_warehouse_branch')) {
            Schema::create('pv_access_right_position_warehouse_branch', function (Blueprint $table) {
                $table->uuid('id')->primary();

                // 1. Relasi ke tabel parent pivot warehouse
                $table->uuid('id_access_right_position_warehouse');
                $table->foreign('id_access_right_position_warehouse', 'fk_arp_warehouse_branch_id')
                    ->references('id')
                    ->on('pv_access_right_position_warehouse')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // 2. Relasi ke Cabang (Aktifkan foreign key jika tabel master cabang / pv_branch_warehouses tersedia)
                $table->uuid('id_branch')->nullable();
                $table->string('name_branch')->nullable(); // Opsional untuk menyimpan nama redundan jika diperlukan

                // 3. Kolom Standar & Metadata
                $table->integer('ordinal')->unsigned()->default(0);
                $table->tinyInteger('is_activated')->nullable();
                $table->string('status', 255)->default('draf');

                $table->string('created_by', 255)->nullable()->index();
                $table->string('updated_by', 255)->nullable()->index();
                $table->nullableTimestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_access_right_position_warehouse_branch');
    }
};
