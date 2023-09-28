<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * SHOPのTOP画面コントローラー
 *
 * TOP画面の処理について一括で管理する。
 */
class ShopTopController extends Controller
{
    /**
     * アイテムモデル
     *
     * @var Item $item
     */
    private $item;

    /**
     * ブランドモデル
     *
     * @var Brand $brand
     */
    private $brand;

    /**
     * カテゴリモデル
     *
     * @var Category $category
     */
    private $category;

    /**
     * 在庫ありと判断する基準の数字
     *
     * @var int $IN_STOCK_NUM
     */
    private const IN_STOCK_NUM = 10;

    /**
     * 在庫わずかと判断する基準の数字
     *
     * @var int $LITTLE_STOCK_NUM
     */
    private const LITTLE_STOCK_NUM = 3;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->brand = new Brand();
        $this->item = new Item();
        $this->category = new Category();
    }

    /**
     * Top画面表示
     *
     * @return View
     */
    public function top()
    {
        $items = $this->item->fetchAllSortByHighSales();
        return view('shop.top', array_merge([
            'items' => $items,
            'inStockNum' => ShopTopController::IN_STOCK_NUM,
            'littleStockNum' => ShopTopController::LITTLE_STOCK_NUM,
        ], $this->fetchCommonInfo()));
    }

    /**
     * 商品詳細画面表示
     *
     * @param int $id
     * @return View
     */
    public function itemDetail($id, Request $request)
    {
        $item = $this->item->findById($id);
        return view('shop.item_detail', array_merge([
            'item' => $item,
            'inStockNum' => ShopTopController::IN_STOCK_NUM,
            'littleStockNum' => ShopTopController::LITTLE_STOCK_NUM,
            'searchConditions'=> $request->all(),
        ], $this->fetchCommonInfo()));
    }

    /**
     * 商品検索処理
     *
     * @param Request $request
     * @return View
     */
    public function search(Request $request)
    {
        $items = $this->item->fetch($request->all());

        return view('shop.top', array_merge([
            'items' => $items,
            'inStockNum' => ShopTopController::IN_STOCK_NUM,
            'littleStockNum' => ShopTopController::LITTLE_STOCK_NUM,
            'searchConditions'=> $request->all(),
        ], $this->fetchCommonInfo()));
    }

    /**
     * 特定商取引に関する法律に基づく表記表示
     *
     * @return View
     */
    public function commercialTransactions()
    {
        return view('shop.commercial_transactions');
    }

    /**
     * マイページ表示
     *
     * @return View
     */
    public function myPage()
    {
        return view('shop.my_page');
    }

    /**
     * 共通情報取得処理
     *
     * 画面に共通して必要な情報取得処理をひとつにまとめる。
     *
     * @return array
     */
    public function fetchCommonInfo()
    {
        return [
            // navigation.blade用にブランド一覧の情報を取得する
            'brands' => $this->brand->findAll(),
            // navigation.blade用にカテゴリー一覧の情報を取得する
            'categories' => $this->category->getCategoryFindAll(),
        ];
    }
}
