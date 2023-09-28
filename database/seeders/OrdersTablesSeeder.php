<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrdersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全商品取得
        $items = Item::all();
        // 注文数のMap
        $quantities = collect([1, 2, 3]);
        // 注文ステータスを取得
        $statusMap = collect(array_keys(Order::STATUS_MAP));

        // ユーザー２人に対してそれぞれ注文履歴を100件登録する
        for ($i = 1; $i <= 2; $i++) {
            for ($j = 1; $j <= 100; $j++) {

                // ordersテーブルにデータ挿入
                $order = new Order([
                    'order_date' => Carbon::today()->addDays($j - 1),
                    'status' => $statusMap->random(),
                    'user_id' => $i
                ]);
                $order->save();

                // 注文商品は商品の中からランダムに5つ挿入する
                $data = $items->random(5)->map(function (Item $item) use ($quantities) {
                    return [
                        'quantity' => $quantities->random(),
                        'item_id' => $item->id,
                    ];
                });
                // order_itemsテーブルにデータ挿入
                $order->orderItems()->createMany($data);
            }
        }
    }
}
