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
        Schema::create('ms_employee_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_employee')->nullable();
            $table->string('username', 255)->nullable();
            $table->string('date_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('website')->nullable();
            $table->string('desc')->nullable();
            $table->string('status')->default('draf');
            $table->integer('ordinal')->nullable();
            $table->timestamp('account_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
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
        Schema::dropIfExists('ms_employee_details');
    }
};
