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
        Schema::create('tr_stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_product')->index(); // Relasi ke ms_products atau ms_product_gift
            $table->string('type', 50); // 'in' (masuk), 'out' (keluar), 'adjustment' (penyesuaian)
            $table->integer('qty'); // Jumlah barang (selalu positif, arah ditentukan kolom 'type')
            $table->integer('stock_before')->default(0); // Stok sebelum transaksi
            $table->integer('stock_after')->default(0);  // Stok sesudah transaksi
            $table->string('reference_no')->nullable(); // No referensi (misal: No Pengajuan Gift / PO)
            $table->uuid('reference_id')->nullable();   // ID transaksi terkait (misal: id pengajuan gift)
            $table->text('remarks')->nullable();        // Keterangan / alasan
            $table->string('created_by', 255)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_pengajuan_gift_detail');
    }
};
