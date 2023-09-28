@extends('admin.layouts.base')
@section('title', 'ブランド一覧')
@section('content')
<div class="pcoded-content">
  <!-- Page-header start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mt-2">ブランド一覧</h2>
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
                </div>
                <div class="card-block">
                  <!-- ここに 検索フォーム を実装 -->
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
                      <!-- ここに ブランド情報表示 を実装 -->
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
            <!-- ここに ページネーション を実装 -->
        </div>
      </div>
    </div>
    <!-- Main-body end -->
  </div>
</div>
@endsection