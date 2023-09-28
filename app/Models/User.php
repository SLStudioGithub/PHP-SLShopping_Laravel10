<?php

namespace App\Models;

use App\Admin\Exceptions\NotFoundException;
use App\Consts\PageConsts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 利用者情報モデル
 */
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * テーブル名
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * user_detailsテーブルとのリレーション定義
     *
     * @return HasOne
     */
    public function detail()
    {
        return $this->hasOne('App\Models\UserDetail', 'user_id', 'id');
    }

    /**
     * 性別一覧の取得
     *
     * @return array
     */
    public function getGenders()
    {
        return UserDetail::GENDER_MAP;
    }

    /**
     * ユーザーの検索＆取得
     *
     * @param string|null $email
     * @param string|null $name
     * @param string|null $nickname
     * @param string|null $birthdayFrom
     * @param string|null $birthdayTo
     * @param array|null $genders
     * @return LengthAwarePaginator
     */
    public function search($email, $name, $nickname, $birthdayFrom, $birthdayTo, $genders)
    {
        // 検索の初期値設定
        // 入力値がnullの時は全性別で検索
        $genders = is_null($genders) ? array_keys($this->getGenders()) : $genders;
        // 入力値がnullの時は19000101~で検索
        $birthdayFrom = new Carbon(is_null($birthdayFrom) ? '1900-01-01' : $birthdayFrom);
        // 入力値がnullの時は~29991231で検索
        $birthdayTo = new Carbon(is_null($birthdayTo) ? '2999-12-31' : $birthdayTo);

        // ユーザーの検索
        return $this->fetch(
            $email,
            $name,
            $nickname,
            $birthdayFrom->toDateString(),
            $birthdayTo->toDateString(),
            $genders
        );
    }

    /**
     * usersテーブルから検索＆取得
     *
     * @param string|null $email
     * @param string|null $name
     * @param string|null $nickname
     * @param string $birthdayFrom
     * @param string $birthdayTo
     * @param array $genders
     * @return LengthAwarePaginator
     */
    public function fetch($email, $name, $nickname, $birthdayFrom, $birthdayTo,$genders)
    {
        $users = User::query()
            ->where('email', 'like', "%{$email}%") // メールアドレスはあいまい検索
            ->where('name', 'like', "%{$name}%") // 名前はあいまい検索
            ->whereHas('detail', function ($query) use ($nickname, $birthdayFrom, $birthdayTo, $genders) {
                $query->where('nickname', 'like', "%{$nickname}%") // ニックネームはあいまい検索
                    ->whereBetween('birthday', [$birthdayFrom, $birthdayTo]) // 誕生日はfrom~toでbetween検索
                    ->whereIn('gender', $genders); // 性別は複数検索
            })
            ->paginate(PageConsts::ADMIN_NUMBER_OF_PER_PAGE); // 1ページあたり20件表示
        return $users;
    }

    /**
     * ユーザーの詳細情報を取得
     *
     * @param integer $id
     * @throws NotFoundException
     * @return User
     */
    public function findById($id)
    {
        // ユーザーの取得
        $user = User::find($id);
        // ユーザーがnullの場合の考慮
        if (is_null($user)) {
            throw new NotFoundException($id, $this->getTable());
        }
        return $user;
    }
}
