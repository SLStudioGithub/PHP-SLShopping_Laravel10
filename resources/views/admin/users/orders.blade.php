@extends('admin.layouts.base')
@section('title', '注文履歴')
@section('content')
<div class="pcoded-content">
  <!-- Page-header start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mt-2">利用者注文履歴</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Page-header end -->
  <div class="pcoded-inner-content">
    <!-- Main-body start -->
    <div class="main-body">
      <div class="page-wrapper">
        <!-- Page-body start -->
        <div class="page-body">
          @component('admin.components.flashMessage')@endcomponent
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>検索条件</h4>
                  <div class="btn-toolbar">
                    <div class="ml-2">
                      <a href="{{ route('admin.users.detail', ['id' => 1]) }}" class="btn btn-sm btn-default" title="一覧"><i class="fa fa-user"></i><span class="hidden-xs">&nbsp;利用者詳細に戻る</span></a>
                    </div>
                  </div>
                </div>
                <div class="card-block">
                  <form id="search-form" action="{{ route('admin.users.orders', ['id' => $user->id]) }}" method="get">
                    <div class="form-group search-form-box">
                      <label for="order-day-from">注文日(from)</label>
                      <input 
                          type="text" 
                          class="form-control" 
                          id="order-day-from" 
                          name="orderDateFrom"
                          value="{{ isset($_GET['orderDateFrom']) ? $_GET['orderDateFrom'] : '' }}"
                          placeholder="注文日"
                          autocomplete="off">
                  </div>
                  <span>~</span>
                  <div class="form-group search-form-box">
                      <label for="order-day-to">注文日(to)</label>
                      <input 
                          type="text" 
                          class="form-control" 
                          id="order-day-to" 
                          name="orderDateTo"
                          value="{{ isset($_GET['orderDateTo']) ? $_GET['orderDateTo'] : '' }}"
                          placeholder="注文日"
                          autocomplete="off">
                  </div>
                  <div class="form-group search-form-box">
                      <label for="order-status">注文状況</label>
                      <div class="form-check">
                          @foreach ($statuses as $key => $status)
                              <input 
                                  class="form-check-input" 
                                  type="checkbox" 
                                  id="{{ "order-status-{$key}" }}"
                                  name="statuses[]"
                                  {{ isset($_GET['statuses']) && in_array($key, $_GET['statuses']) ? 'checked' : '' }}  value="{{ $key }}"
                                  >
                              <label class="form-check-label" for="{{ "order-status-{$key}" }}">{{ $status }}</label>
                          @endforeach
                      </div>
                  </div>
                  <div class="search-buttons">
                      <a class="btn btn-default" href="{{ route('admin.users.orders', ['id' => $user->id]) }}" role="button">リセット</a>
                      <button type="submit" class="btn btn-primary">検索</button>
                  </div>
                  </form>
                </div>
              </div>
              <!-- Hover table card start -->
              <div class="card">
                <div class="card-header">
                  <h5>検索結果一覧</h5>
                </div>
                <div class="card-block table-border-style">
                  <div class="table-responsive">
                    <table class="table table-hover table-sm">
                      <thead>
                        <tr>
                          <th scope="col">注文日</th>
                          <th scope="col">注文状況</th>
                          <th scope="col" style="width: 5%"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $statuses[$order->status] }}</td>
                            <td><a href="" class="btn btn-primary btn-sm">詳細</a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- Hover table card end -->
            </div>
          </div>
        </div>
        <!-- Page-body end -->
        <div class="box-footer clearfix">
          <div class="pull-right">
              {{ $orders->appends(request()->input())->links() }}
          </div>
        </div>
      </div>
    </div>
    <!-- Main-body end -->
  </div>
</div>
@endsection
