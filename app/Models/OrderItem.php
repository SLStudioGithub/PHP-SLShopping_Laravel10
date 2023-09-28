<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 注文商品情報モデル
 */
class OrderItem extends Model
{
    use SoftDeletes;

    /**
     * テーブル名
     *
     * @var string
     */
    protected $table = 'order_items';

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
        'quantity',
        'item_id',
        'order_id',
    ];

    /**
     * 日付を変形する属性
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}