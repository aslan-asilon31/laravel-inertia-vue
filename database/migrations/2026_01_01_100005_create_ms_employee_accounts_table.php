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

        Schema::create('ms_employee_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_employee')->nullable();
            $table->foreign('id_employee')->references('id')->on('ms_employees')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->timestamp('tgl_verifikasi_email')->nullable();
            $table->rememberToken();
            $table->string('status')->default('draf');
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_employee_accounts');
    }
};
