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
        Schema::create('ms_employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->string('status')->default('draf');
            $table->integer('ordinal')->nullable();
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->nullableTimestamps();
            $table->tinyInteger('is_activated')->nullable();
        });


        Schema::create('employee_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('ms_employee_sessions', function (Blueprint $table) {
            $table->string('id', 128)->primary();                  // Wajib (Session ID Laravel)
            $table->string('user_id', 64)->nullable()->index();     // Wajib (UUID Employee)
            $table->string('ip_address', 45)->nullable();           // Wajib untuk tracking sesi
            $table->text('user_agent')->nullable();                 // Wajib untuk tracking sesi
            $table->longText('payload')->nullable();                           // Wajib (Menyimpan CSRF token & data login)
            $table->integer('last_activity')->nullable()->index();              // Wajib untuk masa aktif sesi

            // Kolom kustom tambahan milik Anda
            $table->boolean('is_logged')->nullable();
            $table->string('sesid', 128)->nullable()->index();
            $table->string('id_employee', 64)->nullable()->index();
            $table->string('code_employee', 64)->nullable();
            $table->string('name_employee', 64)->nullable();
            $table->string('role', 64)->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_employees');
        Schema::dropIfExists('employee_password_reset_tokens');
        Schema::dropIfExists('ms_employee_sessions');
    }
};
