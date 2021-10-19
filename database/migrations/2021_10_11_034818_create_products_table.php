<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('is_free_shipping')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('original_price')->default(0);
            $table->integer('is_gift')->default(0);
            $table->integer('is_hot')->default(0);
            $table->integer('discount')->default(0);
//            $table->foreign('category_id')->references('id')->on('categories')
//                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
