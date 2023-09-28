<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_details', function (Blueprint $table) {
            $table->increments('id')->comment('商品詳細情報ID');
            $table->string('full_description')->length(4000)->comment('完全説明');
            $table->float('length', 8, 1)->default(0)->comment('長辺(mm) 形式:129.3');
            $table->float('width', 8, 1)->default(0)->comment('短辺(mm) 形式:129.3');
            $table->float('height', 8, 1)->default(0)->comment('高さ(mm) 形式:129.3');
            $table->float('weight', 8, 1)->default(0)->comment('重量(kg) 形式:129.3');
            $table->unsignedInteger('item_id')->comment('商品情報ID');
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
        Schema::dropIfExists('item_details');
    }
}
