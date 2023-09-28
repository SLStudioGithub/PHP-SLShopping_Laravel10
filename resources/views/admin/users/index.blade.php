@extends('admin.layouts.base')
@section('title', '利用者一覧')
@section('content')
<div class="pcoded-content">
  <!-- Page-header start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mt-2">利用者一覧</h2>
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
                  <form id="search-form" action="{{ route('admin.users.index') }}" method="get">
                    <div class="form-group search-form-box">
                      <label for="user-email">メールアドレス</label>
                      <input type="text" class="form-control" id="user-email" name="email"
                        value="{{ isset($_GET['email']) ? $_GET['email'] : '' }}" placeholder="メールアドレス">
                    </div>
                    <div class="form-group search-form-box">
                      <label for="user-name">名前</label>
                      <input type="text" class="form-control" id="user-name" name="name"
                        value="{{ isset($_GET['name']) ? $_GET['name'] : '' }}" placeholder="名前">
                    </div>
                    <div class="form-group search-form-box">
                      <label for="user-nickname">ニックネーム</label>
                      <input type="text" class="form-control" id="user-nickname" name="nickname"
                        value="{{ isset($_GET['nickname']) ? $_GET['nickname'] : '' }}" placeholder="ニックネーム">
                    </div>
                    <div class="form-group search-form-box">
                      <label for="user-gender">性別</label>
                      <div class="form-check">
                        @foreach ($genders as $key => $gender)
                        <input class="form-check-input" type="checkbox" id="{{ " gender{$key}" }}" name="genders[]"
                          value="{{ $key }}" {{ isset($_GET['genders']) && in_array($key, $_GET['genders']) ? 'checked'
                          : '' }}>
                        <label class="form-check-label" for="{{ " gender{$key}" }}">{{ $gender }}</label>
                        @endforeach
                      </div>
                    </div>
                    <div class="form-group search-form-box">
                      <label for="birthday-from">誕生日(From)</label>
                      <input type="text" class="form-control" id="birthday-from" name="birthdayFrom"
                        value="{{ isset($_GET['birthdayFrom']) ? $_GET['birthdayFrom'] : '' }}" placeholder="誕生日(From)"
                        autocomplete="off">
                    </div>
                    <span>~</span>
                    <div class="form-group search-form-box">
                      <label for="birthday-to">誕生日(To)</label>
                      <input type="text" class="form-control" id="birthday-to" name="birthdayTo"
                        value="{{ isset($_GET['birthdayTo']) ? $_GET['birthdayTo'] : '' }}" placeholder="誕生日(To)"
                        autocomplete="off">
                    </div>
                    <div class="search-buttons">
                      <a class="btn btn-default" href="{{ route('admin.users.index') }}" role="button">リセット</a>
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
                          <th scope="col">名前</th>
                          <th scope="col">ニックネーム</th>
                          <th scope="col">メールアドレス</th>
                          <th scope="col">誕生日</th>
                          <th scope="col" style="width: 5%"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $user)
                        <tr>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->detail->nickname }}</td>
                          <td>{{ $user->email }}</td>
                          <td>{{ $user->detail->birthday }}</td>
                          <td><a href="{{ route('admin.users.detail', ['id' => $user->id]) }}"
                              class="btn btn-primary btn-sm m-1">詳細</a></td>
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
              {{ $users->appends(request()->input())->links() }}
          </div>
        </div>
      </div>
    </div>
    <!-- Main-body end -->
  </div>
</div>
@endsection