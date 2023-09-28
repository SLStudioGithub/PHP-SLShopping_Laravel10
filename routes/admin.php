<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\UsersController;

Route::get('/', function () {
    return view('admin.welcome');
})->name('login');

// Route::get('/register', [RegisteredUserController::class, 'create'])
//     ->middleware('guest')
//     ->name('register');

// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admins')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| 管理者用ルーティング
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admins'])->group(function () {
    // ダッシュボード
    Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // 管理者一覧画面
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // 管理者新規作成画面
    Route::get('/create', [AdminController::class, 'showCreate'])->name('createView');

    // 管理者新規作成
    Route::post('/create', [AdminController::class, 'create'])->name('create');

    // 管理者詳細画面
    Route::get('/detail/{id}', [AdminController::class, 'detail'])->name('detail');

    // 管理者詳細画面
    Route::get('/edit/{id}', [AdminController::class, 'showEdit'])->name('editView');

    // 管理者編集
    Route::post('/edit/{id}', [AdminController::class, 'edit'])->name('edit');

    // 管理者削除
    Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('delete');

    /**
     * ブランド系のルーティンググループ
     *
     */


    /**
     * 商品系のルーティンググループ
     *
     * ※prefix→どのルーティングでも/admin/itemsからスタートする
     * ※as→どのルーティングのnameもadmin.items.からスタートする
     */
    Route::group([
        'prefix' => 'items',
        'as' => 'items.',
    ], function () {

        // 商品一覧画面
        Route::get('/', [ItemsController::class, 'index'])->name('index');

        // 商品新規作成画面
        Route::get('/create', [ItemsController::class, 'showCreate'])->name('createView');

        // 商品新規登録
        Route::post('/create', [ItemsController::class, 'create'])->name('create');

        // 商品詳細画面
        Route::get('/{id}', [ItemsController::class, 'detail'])->where('id', '[0-9]+')->name('detail');

        // 商品編集画面
        Route::get('/edit/{id}', [ItemsController::class, 'showEdit'])->name('editView');

        // 商品編集
        Route::post('/edit/{id}', [ItemsController::class, 'edit'])->name('edit');

        // 商品削除
        Route::delete('/delete/{id}', [ItemsController::class, 'delete'])->name('delete');

        // 商品在庫数量一覧画面
        Route::get('/stock', [ItemsController::class, 'stockIndex'])->name('stock');

        // 商品在庫数量編集画面
        Route::get('/{id}/stock/edit', [ItemsController::class, 'showStockEdit'])
            ->where('id', '[0-9]+')
            ->name('stockEditView');

        // 商品在庫数編集
        Route::post('/{id}/stock/edit', [ItemsController::class, 'stockEdit'])
            ->where('id', '[0-9]+')
            ->name('stockEdit');
    });

    /**
     * 顧客系のルーティンググループ
     *
     * ※prefix→どのルーティングでも/admin/usersからスタートする
     * ※as→どのルーティングのnameもadmin.users.からスタートする
     */
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {

        // 顧客一覧画面
        Route::get('/', [UsersController::class, 'index'])->name('index');

        // 顧客詳細画面
        Route::get('/{id}', [UsersController::class, 'detail'])->name('detail');

        // 顧客購入履歴画面
        Route::get('/{id}/orders', [UsersController::class, 'orders'])->name('orders');
    });
});