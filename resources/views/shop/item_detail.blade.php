@extends('layouts.shop_layout')
@section('title', 'shop item_detail')
@include('shop.header')
@include('shop.navigation')
@section('content')
<div class="content-area-detail">
    <div class="content-detail">
        <ul class="content-partition-ul">
            <li class="content-partition-li">
                <div class="flexslider">
                    <ul class="slides">
                        @foreach ($item->itemImages as $itemImage)
                            <li data-thumb="{{ asset($itemImage->image->path) }}">
                                <img class="image-item-detail" src="{{ asset($itemImage->image->path) }}">
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="content-partition-li description">
                <div>
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="short-description description">{{ $item->short_description }}</div>
                    <div class="description">価格：￥{{ ($item->price) * (1 - ( $item->discount_percent) / 100) }}</div>
                    @if ($item->discount_percent !== 0)
                        <div class="discount-price description">参考価格:￥{{ $item->price }}  <span>{{ $item->discount_percent }}%OFF</span></div>
                    @endif
                    @if ($item->stock > $inStockNum)
                        <div class="description">在庫あり</div>
                    @elseif ($item->stock > $littleStockNum)
                        <div class="description">在庫わずか 残り：{{ $item->stock }}点</div>
                    @elseif ($item->stock === 0)
                        <div class="description">在庫なし</div>
                    @endif
                    <div class="description">ブランド名：{{ $item->brand->name }}</div>
                    <form class="description" action="#" method="post">
                        @if ($item->stock > $inStockNum)
                            <button type="button" class="btn btn-success">カートに入れる</button>
                        @elseif ($item->stock === 0)
                            <button type="button" class="btn btn-danger">在庫なし</button>
                        @endif
                    </form>
                    <div class="description"s>{{ $item->itemDetail->full_description }}</div>
                </div>
            </li>
        </ul>
    </div>
    <div class="content-partition-li-div">
        <div class="item-detail-table-title">商品詳細</div>
        <div class="content-partition-table table-one">
            <table class="table table-bordered">
                <thead></thead>
                <tbody>
                    <tr>
                        <th class="table-light">長　辺（ mm ）</th>
                        <td>{{ $item->itemDetail->length }}</td>
                    </tr>
                    <tr>
                        <th class="table-light">短　辺（ mm ）</th>
                        <td>{{ $item->itemDetail->width }}</td>
                    </tr>
                    <tr>
                        <th class="table-light">高　さ（ mm ）</th>
                        <td>{{ $item->itemDetail->height }}</td>
                    </tr>
                    <tr>
                        <th class="table-light">重　量（ kg ）</th>
                        <td>{{ $item->itemDetail->weight }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="content-partition-table table-two">
            <table class="table table-bordered">
                <thead></thead>
                <tbody>
                    <tr>
                        <th class="table-light">商品名</th>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <th class="table-light">ブランド名</th>
                        <td>{{ $item->brand->name }}</td>
                    </tr>
                    <tr>
                        <th class="table-light">カテゴリー</th>
                        <td>
                            @foreach ($item->itemCategories as $i_category)
                                <div>{{ $categories['grandChildren'][$i_category->category->id]->gc_name }}>{{ $categories['grandChildren'][$i_category->category->id]->c_name }}>{{ $categories['grandChildren'][$i_category->category->id]->name }}</div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">販売開始日</th>
                        <td>{{ $item->created_at->format('Y年m月d日') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@include('shop.footer')