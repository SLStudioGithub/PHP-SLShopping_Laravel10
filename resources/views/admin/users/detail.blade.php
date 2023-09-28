@extends('admin.layouts.base')
@section('title', '利用者詳細')
@section('content')
<div class="pcoded-content">
  <!-- Page-header start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mt-2">利用者詳細</h2>
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
        <!-- Page body start -->
        <div class="page-body">
          <div class="row">
            <div class="col-sm-12">
              <!-- Basic Form Inputs card start -->
              <div class="card">
                <div class="card-header">
                  <h4>詳細</h4>
                  <div class="btn-toolbar">
                    <div class="ml-2">
                      <a class="btn btn-secondary waves-effect waves-light"
                        href="{{ route('admin.users.index') }}">戻る</a>
                    </div>
                    <div class="ml-auto mr-2 btn-group">
                      <a href="" class="btn btn-sm btn-primary" title="一覧"><i class="fa fa-list"></i><span
                          class="hidden-xs">&nbsp;配送先情報一覧</span></a>
                      <a href="{{ route('admin.users.orders', ['id' => $user->id]) }}" class="btn btn-sm btn-primary"
                            title="一覧"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;注文履歴</span></a>

                    </div>
                  </div>
                </div>
              <div class="card-block">
                <div class="form-group row">
                  <div class="col-sm-1">
                    <label for="id">ID</label>
                    <input type="text" class="form-control" value="{{ $user->id }}" readonly>
                  </div>
                  <div class="col-sm-11">
                    <label for="name">利用者名</label>
                    <input type="text" id="name" value="{{ $user->name }}" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12">
                    <label for="nickname">ニックネーム</label>
                    <input type="text" id="nickname" value="{{ $user->detail->nickname }}" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12">
                    <label for="email">メールアドレス</label>
                    <input type="text" id="email" value="{{ $user->email }}" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6">
                    <label for="birthday">誕生日</label>
                    <input type="text" id="birthday" value="{{ $user->detail->birthday }}" class="form-control" readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="gender">性別</label>
                    <input type="text" id="gender" value="{{ $genders[$user->detail->gender] }}" class="form-control" readonly>
                  </div>
                </div>
              </div>
            </div>
            <!-- Basic Form Inputs card end -->
          </div>
        </div>
      </div>
      <!-- Page body end -->
    </div>
  </div>
  <!-- Main-body end -->
</div>
</div>
@endsection