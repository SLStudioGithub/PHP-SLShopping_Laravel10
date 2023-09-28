<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * カテゴリー情報モデル
 */
class Category extends Model
{
    use SoftDeletes;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'categories';

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
        'name',
        'parent_category_id',
    ];

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
     * カテゴリ情報全件取得
     *
     * カテゴリーは親、子、孫カテゴリーを全てJOINして取得する。
     *
     * @return Collection
     */
    public function getCategoryFindAll()
    {
        $categories = $this->findAllToJoin()->groupBy([
            'gc_id',
            'c_id',
            'parent_category_id',
        ], $preserveKeys = true);

        // 親カテゴリーの取得
        $parents = collect();
        foreach ($categories->get(0)->get(0)->get(0) as $parent) {
            $parents->put($parent->id, $parent);
        }

        // 子カテゴリーを取得するためのkeyを取得し、0番目には親が確実に入るので除いておく
        $categoryKeys = $categories->keys();
        $categoryKeys->pull(0);

        // 子カテゴリーの取得
        $children = collect();
        foreach ($categoryKeys as $key) {
            foreach ($categories->get(0)->get($key)->get($key) as $child) {
                $children->put($child->id, $child);
            }
        }

        // 親と子カテゴリーを取得し終わったら、元データから取得済みデータを消去する
        $categories->pull(0);
        // 孫カテゴリーを取得するためのkeyを取得
        $childrenKeys = $children->keys();

        // 孫カテゴリーの取得
        $grandChildren = collect();
        foreach ($categoryKeys as $key) {
            foreach ($childrenKeys as $childKey) {
                if (empty($categories->get($key)->get($childKey))) {
                    continue;
                }
                foreach ($categories->get($key)->get($childKey)->get($childKey) as $grandChild) {
                    $grandChildren->put($grandChild->id, $grandChild);
                }
            }
        }

        return collect([
            'parents' => $parents,
            'children' => $children,
            'grandChildren' => $grandChildren
        ]);
    }

    /**
     * 孫、子をjoinして全件取得
     *
     * @return Collection
     */
    private function findAllToJoin()
    {
        $query = self::selectRaw(
            'id,
            name,
            parent_category_id,
            deleted_at,
            COALESCE(c_c.c_id, 0) as c_id,
            c_c.c_name,
            COALESCE(gc_c.gc_id, 0) as gc_id,
            gc_c.gc_name'
        )
        ->from('categories');

        // categoriesテーブルに対して子カテゴリー情報をJOIN
        $childCategorySql = DB::table('categories as child_c')
            ->select([
                DB::raw('COALESCE(child_c.id, 0) as c_id'),
                'child_c.name as c_name',
                'child_c.parent_category_id as c_parent_category_id'
            ]);
        $query->leftJoinSub($childCategorySql, 'c_c', function ($join) {
            $join->on('parent_category_id', '=', 'c_c.c_id');
        });

        // 子カテゴリー情報に対して孫カテゴリー情報をJOIN
        $grandchildCategorySql = DB::table('categories as grandchild_c')
            ->select([
                DB::raw('COALESCE(grandchild_c.id, 0) as gc_id'),
                'grandchild_c.name as gc_name',
            ]);
        $query->leftJoinSub($grandchildCategorySql, 'gc_c', function ($join) {
            $join->on('c_parent_category_id', '=', 'gc_c.gc_id');
        });

        $query->orderby('id', 'asc')
            ->orderby('parent_category_id', 'asc');

        return $query->get();
    }
}
