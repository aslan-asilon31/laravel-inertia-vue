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
        Schema::create('access_right', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('id_access_right_group')->nullable();
            $table->foreign('id_access_right_group', 'id_access_right_group')
                ->references('id')
                ->on('access_right_group')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name')->nullable();
            $table->integer('ordinal')->unsigned()->default(0);
            $table->tinyInteger('is_activated')->nullable();
            $table->string('status')->default('draf');
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_right');
    }
};
