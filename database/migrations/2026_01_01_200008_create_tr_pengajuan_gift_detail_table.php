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
        Schema::create('tr_pengajuan_gift_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pengajuan_gift_header')->nullable();
            $table->uuid('id_product')->nullable();
            $table->string('name_product')->nullable();
            $table->integer('stock')->default(0);
            $table->text('remarks')->nullable();
            $table->string('status_request')->default('waiting');
            $table->string('status_priority')->default('normal');
            $table->string('status')->default('terbit');
            $table->integer('ordinal')->nullable();
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->nullableTimestamps();
            $table->tinyInteger('is_activated')->nullable();
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
