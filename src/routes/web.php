<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ホームページ
Route::get('/', function () {
    return view('welcome');
});

// メール認証ページ
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // メール認証ビュー
})->middleware('auth')->name('verification.notice');

// メール認証の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // メール認証を完了
    return redirect('/dashboard'); // 認証後のリダイレクト
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メールの再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ユーザー登録ページ
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// POSTリクエストを処理するルート
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest'])
    ->name('register');

// ログインページ
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// その他のルート
Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/post/create', function () {
    return '出品ページ (仮)';
})->name('post.create');

Route::get('/mypage', function () {
    return view('auth.update-profile-information');
})->middleware(['auth', 'verified'])->name('mypage');
