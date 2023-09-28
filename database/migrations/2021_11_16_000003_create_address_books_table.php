<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_books', function (Blueprint $table) {
            $table->increments('id')->comment('ユーザー基本情報ID');
            $table->string('name')->length(255)->comment('配送先名');
            $table->string('phone')->length(13)->comment('配送先電話番号 形式:000-1234-1234');
            $table->string('postal_code')->length(8)->comment('配送先郵便番号 形式:123-4567');
            $table->string('address')->length(255)->comment('配送先住所');
            $table->tinyInteger('package_drop_flg')->length(1)->comment('置き配フラグ（0=不可、1=可）');
            $table->unsignedInteger('user_id')->comment('ユーザー基本情報ID');
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
        Schema::dropIfExists('address_books');
    }
}
