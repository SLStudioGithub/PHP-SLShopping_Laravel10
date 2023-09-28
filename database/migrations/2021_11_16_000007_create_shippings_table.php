<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->increments('id')->comment('配送情報ID');
            $table->tinyInteger('status')->comment('配送状態 1=商品準備中、2=商品梱包完了、3=発送完了、4=配送完了、99=返却');
            $table->unsignedInteger('order_id')->comment('注文情報ID');
            $table->unsignedInteger('address_book_id')->comment('アドレス帳情報ID');
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
        Schema::dropIfExists('shippings');
    }
}
