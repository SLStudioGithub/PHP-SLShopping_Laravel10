@section('sort')
<div>
    <div class="sort-result">検索結果表示 {{ $items->total() }}件の内{{ $items->firstItem() }}～{{ $items->lastItem() }}件表示</div>
    <select id="sort-select" name="sort" class="sort-select">
        @foreach (SortConsts::SORT_LIST as $sortKey => $sortValue)
            @if (isset($searchConditions['sort']) && $searchConditions['sort'] === $sortKey)
                <option value="{{ $sortKey }}" selected>{{ $sortValue }}</option>
            @else
                <option value="{{ $sortKey }}">{{ $sortValue }}</option>
            @endif
        @endforeach
    </select>
</div>
@endsection