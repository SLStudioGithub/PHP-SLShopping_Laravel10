<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品画像情報モデル
 */
class ItemImage extends Model
{
    use SoftDeletes;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'item_images';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'image_id',
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
     * imagesとの多対１リレーション定義
     *
     * @return belongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
