<?php

namespace App\Models;

use App\Admin\Exceptions\NotDeletedException;
use App\Admin\Exceptions\NotFoundException;
use App\Consts\PageConsts;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 商品情報モデル
 */
class Item extends Model
{
    use SoftDeletes;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'items';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * テーブルのデフォルト値設定
     *
     * @var array
     */
    protected $attributes = [
        'stock' => 0,
    ];

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_description',
        'price',
        'discount_percent',
        'stock',
        'brand_id',
    ];

    /**
     * item_detailsとの１対１リレーション定義
     *
     * @return HasOne
     */
    public function itemDetail()
    {
        return $this->hasOne(ItemDetail::class);
    }

    /**
     * brandsとの多対１リレーション定義
     *
     * @return belongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * item_categoriesとの１対多リレーション定義
     *
     * @return HasMany
     */
    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class);
    }

    /**
     * item_imagesとの１対多リレーション定義
     *
     * @return HasMany
     */
    public function itemImages()
    {
        return $this->hasMany(ItemImage::class);
    }

    /**
     * 商品が削除可能か判定
     *
     * @return boolean
     */
    private function isDeletable()
    {
        $statuses = [
            Order::ORDERED,
            Order::PAYMENT_INFO_CONFIRMED,
            Order::READY_TO_SHIP
        ];
        $id = $this->id;
        $isExistsOrder = Order::query()
            ->whereIn('status', $statuses)
            ->whereHas('orderItems', function ($q) use ($id) {
                $q->where('item_id', $id);
            })
            ->exists();
        // 存在する場合は削除不可
        return !$isExistsOrder;
    }

    /**
     * 管理向けの検索＆取得
     *
     * @param integer|null $id
     * @param string|null $name
     * @param integer $lowPrice
     * @param integer $highPrice
     * @param integer|null $brandId
     * @param integer|null $categoryId
     * @param integer $lowStock
     * @param integer $highStock
     * @return LengthAwarePaginator
     */
    public function fetchForAdmin(
        $id,
        $name,
        $lowPrice,
        $highPrice,
        $brandId,
        $categoryId,
        $lowStock,
        $highStock
    )
    {
        // 下限金額はnullの時0を指定
        $lowPrice = is_null($lowPrice) ? 0 : $lowPrice;
        // 上限金額はnullの時1000000を指定
        $highPrice = is_null($highPrice) ? 1000000 : $highPrice;
        // 下限在庫数はnullの時0を指定
        $lowStock = is_null($lowStock) ? 0 : $lowStock;
        // 上限在庫数はnullの時999999999を指定
        $highStock = is_null($highStock) ? 999999999 : $highStock;

        // joinのクエリを取得
        $query = $this->getItemsQuery();
        // 引き続きクエリの検索を追加する
        $query->where('i_c.deleted_at', null);
        if (!is_null($id)) {
            $query->where('items.id', $id); // IDは完全一致
        }
        if (!is_null($name)) {
            $query->where('items.name', 'like', "%$name%"); // 名前はあいまい検索
        }
        if (!is_null($brandId)) {
            $query->where('items.brand_id', $brandId);
        }
        if (!is_null($categoryId)) {
            $query->where('p_id', $categoryId); // 親カテゴリーをjoinした情報から探す
        }
        $items = $query->whereBetween('items.price', [$lowPrice, $highPrice]) // 金額は範囲検索
            ->whereBetween('items.stock', [$lowStock, $highStock]) // 在庫は範囲検索
            ->groupBy('items.id')
            ->orderBy('items.id') // 商品IDでソート
            ->paginate(PageConsts::ADMIN_NUMBER_OF_PER_PAGE);
        return $this->splitMultiCategories($items);
    }

    /**
     * itemsテーブルの詳細情報をIDで取得
     *
     * @param integer $id
     * @return Item
     */
    public function findById($id)
    {
        // クエリを取得
        $query = $this->getItemsQuery();
        // IDで検索して最初のデータを取得
        $item = $query->groupBy('items.id')
            ->where('items.id', $id)
            ->where('i_c.deleted_at', null)
            ->first();
        // 商品がnullの場合の考慮
        if (is_null($item)) {
            throw new NotFoundException($id, $this->getTable());
        }
        return $this->splitCategories($item);
    }

    /**
     * 商品の新規作成
     *
     * @param string $name
     * @param UploadedFile $mainImage
     * @param string $shortDescription
     * @param integer $price
     * @param integer $discountPercent
     * @param integer $brandId
     * @param array $categoryIds
     * @param float $length
     * @param float $width
     * @param float $height
     * @param float $weight
     * @param string $fullDescription
     * @return Item
     */
    public function create(
        $name,
        $mainImage,
        $shortDescription,
        $price,
        $discountPercent,
        $brandId,
        $categoryIds,
        $length,
        $width,
        $height,
        $weight,
        $fullDescription
    )
    {
        // 商品情報の作成(stockは0で作成)
        $item = new self([
            'name' => $name,
            'short_description' => $shortDescription,
            'price' => $price,
            'discount_percent' => $discountPercent,
            'stock' => 0,
            'brand_id' => $brandId,
        ]);

        // 商品のカテゴリー情報作成
        $categories = collect($categoryIds)
            ->map(function (int $categoryId) {
                return new ItemCategory([
                    'category_id' => $categoryId
                ]);
            });

        // 商品詳細情報の作成
        $itemDetail = new ItemDetail([
            'full_description' => $fullDescription,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'weight' => $weight,
        ]);

        // 永続化処理が複数あるためトランザクションで管理
        DB::transaction(function () use ($item, $categories, $itemDetail, $mainImage) {
            // 商品周りの情報を永続化
            $item->saveItems($itemDetail, $categories);

            // 画像を永続化
            $item->saveImages($mainImage);
        });

        return $item;
    }

    /**
     * 商品編集
     *
     * @param integer $id
     * @param string $name
     * @param UploadedFile|null $mainImage
     * @param string $shortDescription
     * @param integer $price
     * @param integer $discountPercent
     * @param integer $brandId
     * @param array $categoryIds
     * @param float $length
     * @param float $width
     * @param float $height
     * @param float $weight
     * @param string $fullDescription
     * @return Item
     */
    public function edit(
        $id,
        $name,
        $mainImage,
        $shortDescription,
        $price,
        $discountPercent,
        $brandId,
        $categoryIds,
        $length,
        $width,
        $height,
        $weight,
        $fullDescription
    )
    {
        // 商品情報の編集
        $item = $this->findById($id)->fill([
            'name' => $name,
            'short_description' => $shortDescription,
            'price' => $price,
            'discount_percent' => $discountPercent,
            'brand_id' => $brandId,
        ]);
        // 商品情報の更新に必要ないカテゴリー文字列は削除する
        unset($item->category_join_name);
        // 商品に紐づくカテゴリー情報の作成
        $categories = collect($categoryIds)
            ->map(function ($categoryId) {
                return new ItemCategory([
                    'category_id' => $categoryId
                ]);
            });
        // 商品詳細情報の編集
        $itemDetail = $item->itemDetail->fill([
            'full_description' => $fullDescription,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'weight' => $weight,
        ]);

        // 永続化処理が複数あるためトランザクションで管理
        DB::transaction(function () use ($item, $categories, $itemDetail, $mainImage) {
            // 商品周りの情報を永続化
            $item->saveItems($itemDetail, $categories);

            // 画像が入力されている場合
            if (!is_null($mainImage)) {
                // 編集前のメイン画像削除
                // ストレージ内の既存画像削除
                $oldImage = $item->getMainImage();
                $oldImage->deleteInStorage();
                // 編集前のDB画像情報削除
                $oldImage->delete();

                // 新しい画像を永続化
                $item->saveImages($mainImage);
            }
        });

        return $item;
    }

    /**
     * 商品削除
     *
     * @param integer $id
     * @throws NotDeletedException
     * @return Item
     */
    public function deleteById($id)
    {
        // 商品情報取得
        $item = $this->findById($id);
        if (!$item->isDeletable()) {
            throw new NotDeletedException($id, $item->getTable());
        }

        // 永続化処理が複数あるためトランザクションで管理
        DB::transaction(function () use ($item) {
            // item_categoriesテーブルのデータ削除
            $item->itemCategories()->delete();
            // item_imagesテーブルのデータ削除
            $item->itemImages()->delete();
            // item_detailテーブルのデータ削除
            $item->itemDetail()->delete();
            // itemテーブルのデータ削除
            $item->delete();
        });
        return $item;
    }

    /**
     * 在庫数編集
     *
     * @param integer $id
     * @param integer $stock
     * @return Item
     */
    public function editStock($id, $stock)
    {
        // 商品詳細取得して在庫更新
        $item = $this->findById($id)->fill([
            'stock' => $stock
        ]);
        // 商品情報の更新に必要ないカテゴリー文字列は削除する
        unset($item->category_join_name);
        // 永続化
        return $item->saveItems(null, null);
    }

    /**
     * 商品のメイン画像を取得
     *
     * @return Image
     */
    public function getMainImage()
    {
        return $this->itemImages->first(function ($itemImage) {
            return $itemImage->image->main_flg;
        })->image;
    }

    /**
     * itemsテーブルからのjoinクエリを取得
     *
     * @return Builder
     */
    private function getItemsQuery()
    {
        $query = self::select([
            'items.id'
            , 'items.name'
            , 'short_description'
            , 'price'
            , 'discount_percent'
            , 'stock'
            , 'brand_id'
            , 'items.created_at'
            , 'items.updated_at'
            , 'detail.full_description'
            , 'detail.length'
            , 'detail.width'
            , 'detail.height'
            , 'detail.weight'
            , 'item_calc.calc_price'
            , 'p_id'
            , DB::raw("GROUP_CONCAT(CONCAT(p_name, '>', c_name, '>', gc_name)) as category_join_name")
        ])
        ->rightJoin('item_details as detail', 'items.id', '=', 'detail.item_id');

        $priceCalcSql = DB::table('items')
            ->select(['id', DB::raw('(price * (1 - (discount_percent) / 100)) as calc_price')])
            ->toSql();
        $query->rightJoin(DB::raw('(' . $priceCalcSql . ') as item_calc'), 'items.id', '=', 'item_calc.id');

        $itemCategorySql = DB::table('item_categories')
            ->select([
                'item_categories.item_id',
                'item_categories.category_id',
                'item_categories.deleted_at'
            ])
            ->toSql();

        $query->rightJoin(
            DB::raw("({$itemCategorySql}) as i_c"),
            'items.id',
            '=',
            'i_c.item_id'
        );

        // item_categoriesテーブルに対して孫カテゴリー情報をJOIN
        $grandchildCategorySql = DB::table('categories as grandchild_c')
            ->select([
                'grandchild_c.id as gc_id',
                'grandchild_c.name as gc_name',
                'grandchild_c.parent_category_id as gc_parent_category_id'
            ])
            ->toSql();
        $query->rightJoin(
            DB::raw('(' . $grandchildCategorySql . ') as gc_c'),
            'i_c.category_id',
            '=',
            'gc_c.gc_id'
        );

        // 孫カテゴリー情報に対して子カテゴリー情報をJOIN
        $childCategorySql = DB::table('categories as child_c')
            ->select([
                'child_c.id as c_id',
                'child_c.name as c_name',
                'child_c.parent_category_id as c_parent_category_id'
            ])
            ->toSql();
        $query->rightJoin(
            DB::raw('(' . $childCategorySql . ') as c_c'),
            'gc_c.gc_parent_category_id',
            '=',
            'c_c.c_id'
        );

        // 子カテゴリー情報に対して親カテゴリー情報をJOIN
        $parentCategorySql = DB::table('categories as parent_c')
            ->select([
                'parent_c.id as p_id',
                'parent_c.name as p_name'
            ])
            ->toSql();
        $query->rightJoin(
            DB::raw('(' . $parentCategorySql . ') as p_c'),
            'c_c.c_parent_category_id',
            '=',
            'p_c.p_id'
        );

        return $query;
    }

    /**
     * 複数商品情報のコンマ区切りのカテゴリー文字列を分割配列化して格納
     *
     * @param LengthAwarePaginator $items
     * @return LengthAwarePaginator
     */
    private function splitMultiCategories($items)
    {
        $items->each(function ($item, $key) use ($items) {
            $items[$key] = $this->splitCategories($item);
        });
        return $items;
    }

    /**
     * 商品情報のコンマ区切りのカテゴリー文字列を分割配列化して格納
     *
     * @param Item|null $item
     * @return Item|null
     */
    private function splitCategories($item)
    {
        if (!$item) return $item;
        $item->category_join_name = explode(",", $item->category_join_name);
        return $item;
    }

    /**
     * 商品周りの永続化処理
     *
     * @param ItemDetail|null $itemDetail
     * @param Collection|null $categories
     * @return Item
     */
    private function saveItems($itemDetail, $categories)
    {
        // itemsテーブルに保存
        $this->save();

        // item周りの情報が渡った場合のみ永続化
        if (!is_null($itemDetail)) {
            // item_detailsテーブルに保存
            $this->itemDetail()->save($itemDetail);
        }
        if (!is_null($categories)) {
            // item_categoriesのデータはデリート＆インサート
            $this->itemCategories->each(function ($itemCategory) {
                $itemCategory->delete();
            });
            // item_categoriesテーブルに保存
            $this->itemCategories()->saveMany($categories);
        }
        return $this;
    }

    /**
     * 画像周りの永続化
     *
     * @param UploadedFile $mainImage
     * @return void
     */
    private function saveImages($mainImage)
    {
        // メイン画像モデル作成
        $image = new Image(['main_flg' => 1]);

        // 画像を保存してファイルパスを取得(メイン画像はmain.jpgで保存)
        $filePath = $image->saveForStorage(
            $mainImage,
            $this->id,
            Image::MAIN_IMAGE_NAME
        );

        // メイン画像に取得したファイルパスを挿入して永続化
        $image->path = "storage/$filePath";
        $image->save();

        // item_imagesモデル作成
        $itemImages = collect([new ItemImage(['image_id' => $image->id])]);
        // item_imagesのデータはデリート＆インサート
        $this->itemImages->each(function ($itemImage) {
            $itemImage->delete();
        });
        $this->itemImages()->saveMany($itemImages);
    }

    /**
     * itemsテーブル全件を販売数が多い順に並べ替え取得
     *
     * @return LengthAwarePaginator
     */
    public function fetchAllSortByHighSales()
    {
        // 結合したクエリを取得
        $query = $this->joinOrderItems($this->getItemsQuery());
        // おすすめ商品順にソート
        $query->orderby('o_i.o_i_quantity', 'DESC')
            // カテゴリーが複数ある場合のために商品をまとめる
            ->groupBy('items.id');
        return $query->paginate(PageConsts::SHOP_NUMBER_OF_PER_PAGE);
    }

    /**
     * order_itemsテーブルとjoin
     *
     * @param Builder $query
     * @return Builder
     */
    private function joinOrderItems($query)
    {
        // order_itemsテーブルをJOIN
        $orderItemSql = DB::table('order_items')
            ->select([
                'item_id as o_i_item_id',
                DB::raw('SUM(quantity) as o_i_quantity')
            ])
            ->whereRaw('created_at > (NOW() - INTERVAL 1 MONTH)')
            ->whereRaw('created_at <= NOW()')
            ->groupBy('item_id')
            ->toSql();
        $query->leftJoin(
            DB::raw('(' . $orderItemSql . ') as o_i'),
            'items.id',
            '=',
            'o_i.o_i_item_id'
        );
        $query->whereNotNull('items.id')
            ->addSelect('o_i_quantity');
        return $query;
    }

    /**
     * 検索条件に当てはまるitemsテーブル情報取得
     *
     * @param array $searchConditions
     * @return LengthAwarePaginator
     */
    public function fetch($searchConditions)
    {
        // 結合したクエリを取得
        $query = $this->joinOrderItems($this->getItemsQuery())
            // カテゴリーが複数ある場合のために商品をまとめる
            ->groupBy('items.id');

        // アンカー カテゴリーIDを条件に絞る
        if (isset($searchConditions['category_id'])) {
            $categoryId = $searchConditions['category_id'];
            $query->where(function ($query) use ($categoryId) {
                $query->orWhere('gc_id', $categoryId)
                    ->orWhere('c_id', $categoryId)
                    ->orWhere('p_id', $categoryId);
            });
        }
        // チェックボックス 商品金額 商品金額は割引率を適用した値を利用する
        if (isset($searchConditions['price'])) {
            $prices = $searchConditions['price'];
            $query->where(function ($query) use ($prices) {
                foreach ($prices as $price) {
                    $query->orWhere(function ($query) use ($price) {
                        switch ($price) {
                            case '500':
                                $query->where('item_calc.calc_price', '<=', 500);
                                break;
                            case '1000':
                                $query->where('item_calc.calc_price', '>=', 501)
                                    ->where('item_calc.calc_price', '<=', 1000);
                                break;
                            case '2000':
                                $query->where('item_calc.calc_price', '>=', 1001)
                                    ->where('item_calc.calc_price', '<=', 2000);
                                break;
                            case '5000':
                                $query->where('item_calc.calc_price', '>=', 2001)
                                    ->where('item_calc.calc_price', '<=', 5000);
                                break;
                            case '10000':
                                $query->where('item_calc.calc_price', '>=', 5001)
                                    ->where('item_calc.calc_price', '<=', 10000);
                                break;
                            case '30000':
                                $query->where('item_calc.calc_price', '>=', 10001)
                                    ->where('item_calc.calc_price', '<=', 30000);
                                break;
                            case '50000':
                                $query->where('item_calc.calc_price', '>=', 30001)
                                    ->where('item_calc.calc_price', '<=', 50000);
                                break;
                            case '50001':
                                $query->where('item_calc.calc_price', '>=', 50001);
                                break;
                        }
                    });
                }
            });
        }
        // チェックボックス　割引率
        if (isset($searchConditions['discount_percent'])) {
            $discountPercents = $searchConditions['discount_percent'];
            $query->where(function ($query) use ($discountPercents) {
                foreach ($discountPercents as $discountPercent) {
                    $query->orWhere(function ($query) use ($discountPercent) {
                        switch ($discountPercent) {
                            case '10':
                                $query->where('discount_percent', '<=', 10);
                                break;
                            case '20':
                                $query->where('discount_percent', '>=', 11)
                                    ->where('discount_percent', '<=', 20);
                                break;
                            case '30':
                                $query->where('discount_percent', '>=', 21)
                                    ->where('discount_percent', '<=', 30);
                                break;
                            case '40':
                                $query->where('discount_percent', '>=', 31)
                                    ->where('discount_percent', '<=', 40);
                                break;
                            case '50':
                                $query->where('discount_percent', '>=', 41)
                                    ->where('discount_percent', '<=', 50);
                                break;
                            case '51':
                                $query->where('discount_percent', '>=', 51);
                                break;
                        }
                    });
                }
            });
        }
        // チェックボックス　在庫あり
        if (isset($searchConditions['in_stock'])) {
            $query->where('stock', '>', 0);
        }
        // セレクトブランド　brand_id指定
        if (isset($searchConditions['brand_id'])) {
            $query->where('brand_id', $searchConditions['brand_id']);
        }
        // 商品名検索　前後あいまい検索
        if (isset($searchConditions['item_name'])) {
            $query->where('items.name', 'like', '%' . $searchConditions['item_name'] . '%');
        }
        //  ソート機能
        if (isset($searchConditions['sort'])) {
            switch ($searchConditions['sort']) {
                case 'top':
                    $query->orderby('o_i.o_i_quantity', 'DESC');
                    break;
                case 'low':
                    $query->orderby('item_calc.calc_price', 'ASC');
                    break;
                case 'high':
                    $query->orderby('item_calc.calc_price', 'DESC');
                    break;
                case 'new':
                    $query->orderby('items.updated_at', 'DESC');
                    break;
            }
        } else {
            // ソート条件がない場合は必ずおすすめ商品順（現在から１ヶ月以内のオーダー商品情報の注文数総数が多いとおすすめ）
            $query->orderby('o_i.o_i_quantity', 'DESC');
        }

        // SQLをLogで確認　ログの場所　storage\logs\laravel.log
        Log::debug('fetch result = ', [
            'SQL' => $query->toSql(),
            'BIND' => $query->getBindings()
        ]);

        return $query->paginate(PageConsts::SHOP_NUMBER_OF_PER_PAGE);
    }
}
