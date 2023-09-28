<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 利用者詳細情報モデル
 */
class UserDetail extends Model
{
    use SoftDeletes;

    /**
     * 性別Map
     * 
     * @var array
     */
    public const GENDER_MAP = [
        1 => '男',
        2 => '女',
        3 => 'どちらでもない',
    ];

    /**
     * テーブル名
     *
     * @var string
     */
    protected $table = 'user_details';

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
        'nickname',
        'birthday',
        'gender',
        'phone',
        'postal_code',
        'address',
        'user_id',
    ];

    /**
     * 日付を変形する属性
     *
     * @var array
     */
    protected $dates = [
        'birthday',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}