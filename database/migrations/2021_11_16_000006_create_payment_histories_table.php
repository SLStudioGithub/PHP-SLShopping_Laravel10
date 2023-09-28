<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->increments('id')->comment('ユーザー基本情報ID');
            $table->integer('amount')->comment('支払金額');
            $table->tinyInteger('status')->comment('支払形態 1=クレジット、2=コンビニ決済、3=銀行振込、4=郵便振替、5=代引き');
            $table->datetime('completed_at')->nullable()->comment('支払完了日');
            $table->unsignedInteger('order_id')->comment('注文情報ID');
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
        Schema::dropIfExists('payment_histories');
    }
}
