<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * 顧客画面コントローラー
 *
 * 顧客画面の処理について一括で管理する。
 */
class UsersController extends Controller
{
    /**
     * 顧客モデル
     *
     * @var User $user
     */
    private $user;

    /**
     * 顧客モデル
     *
     * @var Order $order
     */
    private $order;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->user = new User();
        $this->order = new Order();
    }

    /**
     * 顧客一覧＆検索画面
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        // 顧客の検索と取得
        $users = $this->user->search(
            $request->email,
            $request->name,
            $request->nickname,
            $request->birthdayFrom,
            $request->birthdayTo,
            $request->genders
        );
        // 検索フォーム用に性別の一覧を取得
        $genders = $this->user->getGenders();
        return view('admin.users.index', compact('users', 'genders'));
    }

    /**
     * 顧客詳細画面
     *
     * @param integer $id
     * @return View
     */
    public function detail($id)
    {
        // 顧客の詳細情報を取得
        $user = $this->user->findById($id);
        // 性別表示用に性別一覧を取得
        $genders = $this->user->getGenders();
        return view('admin.users.detail', compact('user', 'genders'));
    }

    /**
     * 顧客の注文履歴画面
     *
     * @param integer $id
     * @param Request $request
     * @return View
     */
    public function orders($id, Request $request)
    {
        // 顧客の詳細情報を取得
        $user = $this->user->findById($id);
        // 注文履歴の取得
        $orders = $this->order->search(
            $request->orderDateFrom,
            $request->orderDateTo,
            $request->statuses,
            $id
        );
        // 注文ステータス一覧の取得
        $statuses = $this->order->getStatuses();
        return view('admin.users.orders', compact('orders', 'statuses', 'user'));
    }
}