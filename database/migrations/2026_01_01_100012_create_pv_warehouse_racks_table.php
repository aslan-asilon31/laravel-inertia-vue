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
        Schema::create('pv_warehouse_racks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_warehouse')->nullable();
            $table->string('name_warehouse')->nullable();
            $table->string('id_rack')->nullable();
            $table->string('name_rack')->nullable();
            $table->integer('ordinal')->nullable();
            $table->string('status', 255)->default('draf');
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
        Schema::dropIfExists('pv_warehouse_racks');
    }
};
