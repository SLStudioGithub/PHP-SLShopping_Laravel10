<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->increments('id')->comment('ユーザー詳細情報ID');
            $table->string('nickname')->nullable()->comment('ニックネーム')->unique();
            $table->date('birthday')->nullable()->comment('生年月日');
            $table->tinyInteger('gender')->nullable()->comment('性別 1=男性、2=女性、3=どちらでもない');
            $table->string('phone')->length(13)->nullable()->comment('電話番号 形式:000-1234-5678');
            $table->string('postal_code')->length(7)->nullable()->comment('郵便番号 形式:123-4567');
            $table->string('address')->length(255)->nullable()->comment('住所');
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
        Schema::dropIfExists('user_details');
    }
}
