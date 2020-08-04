<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request){
  return $request->user();
});

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// ログインユーザー
Route::get('/user', fn() => Auth::user())->name('user');
// 写真の投稿
Route::post('/photos', 'PhotoController@create')->name('photo.create');
// 写真一覧
Route::get('/photos', 'PhotoController@index')->name('photo.index');
// 写真の詳細画面
Route::get('/photos/{id}', 'PhotoController@show')->name('photo.show');
// コメント
Route::post('/photos/{photo}/comments', 'PhotoController@addComment')->name('photo.comment');
// いいね
Route::put('/photos/{id}/like', 'PhotoController@like')->name('photo.like');
// いいね解除
Route::delete('/photos/{id}/like', 'PhotoController@unlike')->name('photo.unlike');
// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
  $request->session()->regenerateToken();
  
  return response()->json();
});