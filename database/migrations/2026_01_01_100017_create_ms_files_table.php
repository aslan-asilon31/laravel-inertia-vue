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
        Schema::create('ms_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('fileable_id')->nullable();
            $table->string('fileable_type')->nullable();
            $table->index(['fileable_id', 'fileable_type']);

            $table->string('url')->nullable();
            $table->string('type_file')->nullable();
            $table->string('status')->default('draf');

            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->timestamps();
            $table->tinyInteger('is_activated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_files');
    }
};
