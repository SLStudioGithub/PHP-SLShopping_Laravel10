<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id')->comment('商品情報ID');
            $table->string('name')->length(255)->comment('商品名');
            $table->string('short_description')->length(500)->comment('省略説明');
            $table->integer('price')->default(0)->comment('金額');
            $table->integer('discount_percent')->default(0)->comment('割引率 単位%');
            $table->integer('stock')->default(0)->comment('在庫数量');
            $table->unsignedInteger('brand_id')->comment('ブランド情報ID');
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
        Schema::dropIfExists('items');
    }
}
