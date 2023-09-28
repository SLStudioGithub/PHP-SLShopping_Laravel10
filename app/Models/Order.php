<?php

namespace App\Models;

use App\Consts\PageConsts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 注文履歴情報モデル
 */
class Order extends Model
{
    use SoftDeletes;

    /**
     * @var int 注文済みステータス
     */
    public const ORDERED = 1;

    /**
     * @var int 支払い情報確認済みステータス
     */
    public const PAYMENT_INFO_CONFIRMED = 2;

    /**
     * @var int 発送準備中ステータス
     */
    public const READY_TO_SHIP = 3;

    /**
     * @var int 配送完了ステータス
     */
    public const DELIVERY_COMPLETED = 4;

    /**
     * @var int 返却ステータス
     */
    public const RETURN = 99;

    /**
     * 注文ステータスMap
     *
     * @var array
     */
    public const STATUS_MAP = [
        self::ORDERED => '注文済み',
        self::PAYMENT_INFO_CONFIRMED => '支払情報確認済',
        self::READY_TO_SHIP => '発送準備中',
        self::DELIVERY_COMPLETED => '配送完了',
        self::RETURN => '返却',
    ];

    /**
     * テーブル名
     *
     * @var string
     */
    protected $table = 'orders';

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
        'order_date',
        'status',
        'user_id',
    ];

    /**
     * 日付を変形する属性
     *
     * @var array
     */
    protected $dates = [
        'order_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * order_itemsテーブルのリレーション定義
     *
     * @return HasMany
     */
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    /**
     * 注文履歴の検索＆取得
     *
     * @param string|null $orderDateFrom
     * @param string|null $orderDateTo
     * @param array|null $statuses
     * @param integer|null $userId
     * @return LengthAwarePaginator
     */
    public function search($orderDateFrom, $orderDateTo, $statuses, $userId = null)
    {
        // 検索の初期値設定
        // 入力値がnullの時は全ステータスで検索
        $statuses = is_null($statuses) ? array_keys($this->getStatuses()) : $statuses;
        // 入力値がnullの時は19000101~で検索
        $orderDateFrom = new Carbon(is_null($orderDateFrom) ? '1900-01-01' : $orderDateFrom);
        // 入力値がnullの時は~29991231で検索
        $orderDateTo = new Carbon(is_null($orderDateTo) ? '2999-12-31' : $orderDateTo);

        // 注文履歴の検索
        return $this->fetch(
            $orderDateFrom->toDateString(),
            $orderDateTo->toDateString(),
            $statuses,
            $userId
        );
    }

    /**
     * ステータス一覧の取得
     *
     * @return array
     */
    public function getStatuses()
    {
        return Order::STATUS_MAP;
    }

    /**
     * ordersテーブルから検索＆取得
     *
     * @param string $orderDateFrom
     * @param string $orderDateTo
     * @param array $statuses
     * @param integer|null $userId
     * @return LengthAwarePaginator
     */
    public function fetch($orderDateFrom, $orderDateTo, $statuses, $userId = null)
    {
        $orders = Order::query()
            ->whereIn('status', $statuses) // ステータスの複数検索
            ->whereBetween('order_date', [$orderDateFrom, $orderDateTo]) // 注文日の範囲検索
            ->when(!is_null($userId), function ($query) use ($userId) { // ユーザーIDがnullでない時のみ適用
                return $query->where('user_id', $userId);
            })
            ->paginate(PageConsts::ADMIN_NUMBER_OF_PER_PAGE);
        return $orders;
    }
}