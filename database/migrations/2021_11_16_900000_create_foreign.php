<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->OnDelete('cascade');
        });

        Schema::table('address_books', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->OnDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->OnDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->OnDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->OnDelete('cascade');
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->OnDelete('cascade');
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->OnDelete('cascade');
            $table->foreign('address_book_id')->references('id')->on('address_books')->OnDelete('cascade');
        });

        Schema::table('shipping_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->OnDelete('cascade');
            $table->foreign('shipping_id')->references('id')->on('shippings')->OnDelete('cascade');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->OnDelete('cascade');
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->OnDelete('cascade');
        });

        Schema::table('item_categories', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->OnDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->OnDelete('cascade');
        });

        Schema::table('item_images', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->OnDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->OnDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropForeign('user_details_user_id_foreign');
        });

        Schema::table('address_books', function (Blueprint $table) {
            $table->dropForeign('address_books_user_id_foreign');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_item_id_foreign');
            $table->dropForeign('order_items_order_id_foreign');
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropForeign('payment_histories_order_id_foreign');
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign('shippings_order_id_foreign');
            $table->dropForeign('shippings_address_book_id_foreign');
        });

        Schema::table('shipping_items', function (Blueprint $table) {
            $table->dropForeign('shipping_items_item_id_foreign');
            $table->dropForeign('shipping_items_shipping_id_foreign');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_brand_id_foreign');
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->dropForeign('item_details_item_id_foreign');
        });

        Schema::table('item_categories', function (Blueprint $table) {
            $table->dropForeign('item_categories_item_id_foreign');
            $table->dropForeign('item_categories_category_id_foreign');
        });

        Schema::table('item_images', function (Blueprint $table) {
            $table->dropForeign('item_images_item_id_foreign');
            $table->dropForeign('item_images_image_id_foreign');
        });
    }
}
