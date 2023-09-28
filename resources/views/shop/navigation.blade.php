@section('navigation')
<div class="navigation">
    <form id="navigation-form" class="navigation-form" action="{{ route('shop.search') }}" method="GET">
        <ul class="navigation-menu">
            <header>
                <h3>カテゴリー</h3>
            </header>
            <li class="navigation-search">
                <ul class="category-list">
                    @foreach ($categories->get('parents') as $parent)
                        <li
                            @if (isset($searchConditions['category_id']))
                                @if ($searchConditions['category_id'] == $parent->id)
                                    class="p-category category-selecting"
                                @else
                                    class="p-category"
                                @endif
                            @else
                                class="p-category"
                            @endif
                        >
                            <a class="category-anchor" href="{{ route('shop.search') . '?#' }}" data-category-id="{{ $parent->id }}">
                                {{ $parent->name }}
                            </a>
                        </li>
                        @foreach ($categories->get('children') as $child)
                            @if ($child->parent_category_id == $parent->id)
                                <li
                                    @if (isset($searchConditions['category_id']))
                                        @if ($searchConditions['category_id'] == $child->id)
                                            class="c-category category-selecting"
                                        @else
                                            class="c-category"
                                        @endif
                                    @else
                                        class="c-category"
                                    @endif
                                >
                                    <a class="category-anchor" href="{{ route('shop.search') . '?#' }}" data-category-id="{{ $child->id }}">
                                        {{ $child->name }}
                                    </a>
                                </li>
                                @foreach ($categories->get('grandChildren') as $grandChild)
                                    @if ($grandChild->parent_category_id == $child->id)
                                        <li
                                            @if (isset($searchConditions['category_id']))
                                                @if ($searchConditions['category_id'] == $grandChild->id)
                                                    class="gc-category category-selecting"
                                                @else
                                                    class="gc-category"
                                                @endif
                                            @else
                                                class="gc-category"
                                            @endif
                                        >
                                            <a class="category-anchor" href="{{ route('shop.search') . '?#' }}" data-category-id="{{ $grandChild->id }}">
                                                {{ $grandChild->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            </li>
            <header>
                <h3>検索条件</h3>
            </header>
            <header>
                <h4>商品金額</h4>
            </header>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="500"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '500' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >~500円以下</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="1000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '1000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >501~1000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="2000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '2000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >1001~2000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="5000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '5000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >2001~5000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="10000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '10000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >5001~10000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="30000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '30000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >10001~30000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="50000"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '50000' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >30001~50000円</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="price[]" value="50001"
                    @if (isset($searchConditions['price']))
                        @foreach ($searchConditions['price'] as $price)
                            {{ $price === '50001' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >50001円以上</label>
            </li>
            <header>
                <h4>割引率</h4>
            </header>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="10"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '10' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >10%以下</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="20"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '20' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >11~20%</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="30"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '30' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >21~30%</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="40"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '40' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >31~40%</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="50"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '50' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >41~50%</label>
            </li>
            <li class="navigation-search">
                <input type="checkbox" name="discount_percent[]" value="51"
                    @if (isset($searchConditions['discount_percent']))
                        @foreach ($searchConditions['discount_percent'] as $percent)
                            {{ $percent === '51' ? 'checked' : '' }}
                        @endforeach
                    @endif
                >
                <label >51%以上</label>
            </li>
            <header>
                <h4>在庫数</h4>
            </header>
            <li class="navigation-search">
                <input type="checkbox" name="in_stock" value="is_stock" {{ isset($searchConditions['in_stock']) ? 'checked' : '' }}>
                <label >在庫あり</label>
            </li>
            <header>
                <h4>ブランド</h4>
            </header>
            <li>
                <select name="brand_id">
                    <option value="">指定なし</option>
                    {{-- 詳細画面作成時に@isset @else @endissetを消去する --}}
                    @isset ($brands)
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"
                                @isset($searchConditions['brand_id'])
                                   {{ ($searchConditions['brand_id'] == $brand->id) ? 'selected' : '' }}
                                @endisset
                            >{{ $brand->name }}</option>
                        @endforeach
                    @else
                        <option value="1">ブランド1</option>
                        <option value="2">ブランド2</option>
                        <option value="3">ブランド3</option>
                        <option value="4">ブランド4</option>
                        <option value="5">ブランド5</option>
                        <option value="6">ブランド6</option>
                    @endisset
                </select>
            </li>
            <header>
                <h4>商品名検索</h4>
            </header>
            <li class="navigation-search">
                <input type="text" name="item_name" value="{{ isset($searchConditions['item_name']) ? $searchConditions['item_name'] : '' }}" placeholder="商品名を入力して下さい">
            </li>
            <li class="navigation-search search-hidden">
                @if (isset($searchConditions['category_id']))
                    <input type="hidden" name="category_id" value="{{ $searchConditions['category_id'] }}">
                @endif
                @if (isset($searchConditions['sort']))
                    <input id='input_sort' type="hidden" name="sort" value="{{ $searchConditions['sort'] }}">
                @else
                    <input id='input_sort' type="hidden" name="sort" value="top">
                @endif
            </li>
            <li class="navigation-search">
                <input type="submit" id="search-btn" value="検索">
            </li>
        </ul>
    </form>
</div>
@endsection