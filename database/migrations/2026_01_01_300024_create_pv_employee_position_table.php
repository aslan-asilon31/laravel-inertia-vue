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
        Schema::create('pv_employee_position', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('id_position');
            $table->foreign('id_position')->references('id')->on('ms_positions')->onDelete('cascade')->onUpdate('cascade');

            $table->uuid('id_employee');
            $table->foreign('id_employee')->references('id')->on('ms_employees')->onDelete('cascade')->onUpdate('cascade');
            $table->string('status')->default('draf');
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
        Schema::dropIfExists('pv_employee_position');
    }
};
