<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use App\Models\ItemImage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
        * 基本は現状のテーブル内ある既存データを削除してから新しいデータを挿入する。
        * マスターにあたるブランド情報とカテゴリー情報のみ設定し、それ以外は型に合わせ作成。
        */
        // truncateを実行しようとすると外部キー制約が入るので一旦ここではチェックを外す
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $dateNow = Carbon::now();
        // $dateNow = $dt->toDateTimeString();

        Brand::truncate();
        Brand::insert([
            [
                'name' => "BEAMS",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "LOWRYS FARM",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "CECIL McBEE",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "GAP",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "MERCURYDUO",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "NATURAL BEAUTY BASIC",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "LIZ LISA",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ZARA",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "23区",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "Theory",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ROPE' PICNIC",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "UNIQLO",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "staub",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "Le Creuset",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "Vermicular",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "DANSK",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ニトリ",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "野田琺瑯",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "Vita Craft",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "和平フレイズ",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "T-fal",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "アイリスオーヤマ",
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            // 22ブランド
        ]);

        Category::truncate();
        Category::insert([
            [
                'name' => "レディースファッション",
                'parent_category_id' => 0,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "服＆ファッション小物",
                'parent_category_id' => 1,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "トップス",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "コート・ジャケット",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "スカート",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ワンピース・ドレス",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "パンツ",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "靴下・レッグウェア",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ストール",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "サングラス",
                'parent_category_id' => 2,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "キッチン用品",
                'parent_category_id' => 0,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "鍋＆フライパン",
                'parent_category_id' => 11,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "両手鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "片手鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "フライパン・炒め鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "鋳物ホーロー鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "圧力鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "蒸し器・せいろ",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "揚げ鍋・天ぷら鍋",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "ふた",
                'parent_category_id' => 12,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "シンク周り",
                'parent_category_id' => 11,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            [
                'name' => "スポンジ",
                'parent_category_id' => 21,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ],
            // 22カテゴリー 内親カテゴリー2 小カテゴリー3 孫カテゴリー17
        ]);

        Item::truncate();
        for ($i = 1; $i <= 100; $i++) {
            Item::insert([
                'name' => "商品名{$i}",
                'short_description' => "短い説明で一覧に表示{$i}短い説明で一覧に表示{$i}短い説明で一覧に表示{$i}短い説明で一覧に表示{$i}",
                'price' => rand(1, 20) * 1000,
                'discount_percent' => rand(0, 50),
                'stock' => rand(10, 20),
                'brand_id' => rand(1, 22),
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }

        ItemDetail::truncate();
        for ($i = 1; $i <= 100; $i++) {
            ItemDetail::insert([
                'full_description' => ("長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"
                    . "長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"
                    . "長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"
                    . "長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"
                    . "長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"
                    . "長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}長い説明で詳細に表示{$i}"),
                // 50から200の間で小数点以下1桁までのランダムな少数値を取得する
                'length' => round(50 + mt_rand() / mt_getrandmax() * (200 - 50), 1),
                'width' => round(50 + mt_rand() / mt_getrandmax() * (200 - 50), 1),
                'height' => round(50 + mt_rand() / mt_getrandmax() * (200 - 50), 1),
                'weight' => round(50 + mt_rand() / mt_getrandmax() * (200 - 50), 1),
                'item_id' => $i,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }

        Image::truncate();
        for ($i = 1; $i <= 100; $i++) {
            $imageNum = rand(1, 20);
            Image::insert([
                'path' => "image/item-{$imageNum}.jpg",
                'main_flg' => 1,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }

        ItemImage::truncate();
        for ($i = 1; $i <= 100; $i++) {
            ItemImage::insert([
                'item_id' => $i,
                'image_id' => $i,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }

        ItemCategory::truncate();
        for ($i = 1; $i <= 100; $i++) {
            $categoryId = rand(1, 22);
            while (true) {
                // カテゴリー情報の親カテゴリー、子カテゴリーは含めない
                if ($categoryId != 1 && $categoryId != 2 && $categoryId != 11 && $categoryId != 12 && $categoryId != 21) {
                    break;
                }
                $categoryId = rand(1, 22);
            }
            ItemCategory::insert([
                'item_id' => $i,
                'category_id' => $categoryId,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }

        // truncate実行するために外したチェックを元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
