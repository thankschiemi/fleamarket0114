<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PurchasesController;


// ユーザー登録
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// メール認証
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ログイン
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// ログアウト
Route::post('/logout', function (Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    // プロフィール閲覧ページ
    Route::get('/mypage', [ProductController::class, 'showProfile'])->name('mypage');

    // プロフィール編集ページ
    Route::get('/mypage/profile', [UserController::class, 'editProfile'])->name('mypage.profile');
    Route::put('/mypage/profile', [UserController::class, 'updateProfile'])->name('mypage.profile.update');
});




Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('/item/{item_id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware(['auth'])->group(
    function () {
        Route::post('/favorites/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    }
);

Route::middleware(['auth'])->group(function () {
    Route::get('/purchases', [PurchasesController::class, 'index'])->name('purchases.index');
    Route::get('/purchase/{item_id}', [PurchasesController::class, 'show'])->name('purchase.show');

    Route::get('/purchase/{item_id}/complete', [PurchasesController::class, 'complete'])->name('purchase.complete');
    Route::post('/purchase/{item_id}/complete', [PurchasesController::class, 'complete']);

    Route::get('/purchase/address/{item_id}', [PurchasesController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchasesController::class, 'updateAddress'])->name('shipping.update');

    Route::post('/purchase/{item_id}/checkout', [PurchasesController::class, 'checkout'])->name('purchase.checkout');
});


Route::middleware('auth')->group(function () {
    Route::get('/sell', [ProductController::class, 'create'])->name('products.create');
    Route::post('/sell', [ProductController::class, 'store'])->name('products.store');
});
