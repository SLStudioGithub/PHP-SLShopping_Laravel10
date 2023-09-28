<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * 画像情報モデル
 */
class Image extends Model
{
    use SoftDeletes;

    /**
     * メイン画像名
     */
    public const MAIN_IMAGE_NAME = 'main.jpg';

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'images';

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
        'path',
        'main_flg',
    ];

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
     * ストレージに画像ファイル保存
     *
     * @param UploadedFile $file
     * @param string $dirName
     * @param string $fileName
     * @return string
     */
    public function saveForStorage(
        $file,
        $dirName,
        $fileName
    )
    {
        // ストレージに保存してファイルパスを返す
        return Storage::disk('public')->putFileAs(
            $dirName,
            $file,
            $fileName
        );
    }

    /**
     * ストレージのファイル削除
     *
     * @param string $filePath
     * @return void
     */
    public function deleteInStorage()
    {
        Storage::disk('public')->delete($this->path);
    }
}
