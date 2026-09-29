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
        Schema::create('ms_channels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable(); // service center,social media(facebook,instagram,twitter)
            $table->string('status', 255)->default('draf');
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
        Schema::dropIfExists('ms_channels');
    }
};
