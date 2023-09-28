<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_items', function (Blueprint $table) {
            $table->increments('id')->comment('配送商品情報ID');
            $table->integer('quantity')->comment('配送数量');
            $table->unsignedInteger('item_id')->comment('商品情報ID');
            $table->unsignedInteger('shipping_id')->comment('配送情報ID');
            $table->datetime('created_at')->comment('作成日');
            $table->datetime('updated_at')->comment('更新日');
            $table->datetime('deleted_at')->nullable()->comment('削除日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_items');
    }
}
