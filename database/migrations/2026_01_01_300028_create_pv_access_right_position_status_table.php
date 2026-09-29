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
        Schema::create('pv_access_right_position_status', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_access_right_position');
            $table->foreign('id_access_right_position', 'id_access_right_position')->references('id')->on('pv_access_right_position')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('pv_access_right_position_status');
    }
};
