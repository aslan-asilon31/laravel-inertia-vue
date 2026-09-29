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
        if (!Schema::hasTable('pv_access_right_position_warehouse')) {
            Schema::create('pv_access_right_position_warehouse', function (Blueprint $table) {
                $table->uuid('id')->primary();

                // Relasi ke tabel pv_access_right_position
                $table->uuid('id_access_right_position');
                $table->foreign('id_access_right_position', 'pv_id_access_right_position')
                    ->references('id')
                    ->on('pv_access_right_position')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // 🌟 TAMBAHKAN KOLOM INI AGAR TERHUBUNG KE GUDANG (ms_warehouses)
                $table->uuid('id_warehouse');
                $table->foreign('id_warehouse', 'fk_arp_warehouse_id')
                    ->references('id')
                    ->on('ms_warehouses') // Sesuaikan jika nama tabel master gudang Anda berbeda (misal: 'ms_warehouse')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->integer('ordinal')->unsigned()->default(0);
                $table->tinyInteger('is_activated')->nullable();
                $table->string('status')->default('draf');
                $table->string('created_by', 255)->nullable()->index();
                $table->string('updated_by', 255)->nullable()->index();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_access_right_position_warehouse');
    }
};
