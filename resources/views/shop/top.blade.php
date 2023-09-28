@extends('shop.layouts.shop_layout')
@section('title', 'shop index')
@include('shop.header')
@include('shop.sort')
@include('shop.navigation')
@section('content')
<div class="content-area">
    @foreach ($items as $item)
        <div class="content">
            <a class="content-anchor" href="{{ route('shop.item_detail', $item) }}">
                <div class="image-location">
                    @foreach ($item->itemImages as $itemImage)
                        @if ($itemImage->image->main_flg)
                            <img class="item-image" src="{{ asset($itemImage->image->path) }}">
                        @endif
                    @endforeach
                </div>
                <div class="item-description-block">
                    <div class="item-description">
                        <div class="item-short-description">{{ $item->name }} - <span>{{ $item->short_description }}</span></div>
                        <div>￥{{ ($item->price) * (1 - ( $item->discount_percent) / 100) }}</div>
                        @if ($item->discount_percent !== 0)
                            <div>参考価格:￥{{ $item->price }} <span>{{ $item->discount_percent }}%OFF</span></div>
                        @endif
                        @if ($item->stock > $inStockNum)
                            <div>在庫あり</div>
                        @elseif ($item->stock > $littleStockNum)
                            <div>在庫わずか</div>
                        @elseif ($item->stock === 0)
                            <div>在庫なし</div>
                        @endif
                        <div>{{ $item->brand->name }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

@endsection
@section('pagination')
<div class="">
    {{ $items->appends(request()->query())->links() }}
</div>
@endsection
@include('shop.footer')