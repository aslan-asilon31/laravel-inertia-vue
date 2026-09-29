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


    Schema::create('ms_products', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('sync_id', 64)->nullable();
      $table->uuid('id_product_gift')->nullable();
      $table->uuid('id_product_category')->nullable();
      $table->uuid('id_product_type')->nullable();
      $table->string('sku', 128)->nullable();
      $table->string('name')->nullable();
      $table->decimal('selling_price', 15, 2)->nullable();
      $table->decimal('discount_persentage', 5, 2)->nullable();
      $table->decimal('discount_value', 15, 2)->nullable();
      $table->decimal('nett_price', 15, 2)->nullable();
      $table->decimal('weight', 10, 2)->nullable();
      $table->decimal('rating', 4, 2)->nullable();
      $table->decimal('sold_qty', 10, 2)->nullable();
      $table->string('availability', 255)->index()->nullable(); // in-stock, out-off-stock
      $table->string('image_url', 255)->nullable();
      $table->string('status', 255)->default('draf');
      $table->string('highlight_image_url', 255)->nullable();
      $table->string('created_by', 255)->nullable()->index();
      $table->string('updated_by', 255)->nullable()->index();
      $table->timestamps();
      $table->smallInteger('ordinal')->nullable();
      $table->boolean('is_new')->default(true);
      $table->tinyInteger('is_activated')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Schema::dropIfExists('ms_product_branches');
    Schema::dropIfExists('ms_products');
  }
};
