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
        Schema::create('access_right_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_parent')->nullable()->index();
            $table->foreign('id_parent')->references('id')->on('access_right_group')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('status')->default('draf');
            $table->string('category')->nullable();
            $table->string('icon')->nullable();
            $table->integer('ordinal')->nullable();
            $table->integer('ordinal_child')->nullable();
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
        Schema::dropIfExists('access_right_group');
    }
};
