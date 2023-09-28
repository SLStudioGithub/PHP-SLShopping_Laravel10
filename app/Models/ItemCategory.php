<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品カテゴリー情報モデル
 */
class ItemCategory extends Model
{
    use SoftDeletes;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'item_categories';

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
        // 'category' => 'PHP',
    ];

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'category_id',
    ];

    /**
     * itemsとの多対１リレーション定義
     *
     * @return belongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * categoriesとの多対１リレーション定義
     *
     * @return belongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
