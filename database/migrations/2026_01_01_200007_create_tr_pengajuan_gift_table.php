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
        Schema::create('tr_pengajuan_gift', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_employee')->nullable();
            $table->string('name_employee')->nullable();
            $table->uuid('id_channel')->nullable();
            $table->string('name_channel')->nullable();
            $table->uuid('id_customer')->nullable();
            $table->string('name_customer')->nullable();
            $table->string('name')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('ordinal')->nullable();
            $table->string('status', 255)->default('draf'); //draf,terbit,menunggu,disetujui
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
        Schema::dropIfExists('tr_pengajuan_gift');
    }
};
