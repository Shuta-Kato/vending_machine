<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;

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


// Route::get('/', function () {
//     return view('login');
// });

//認証機能系
Route::get('/',function(){
    if(Auth::check()){
        //ログイン状態の場合
        return redirect()->route('products.index');
        //商品一覧ページにリダイレクト
    }else{
        //ログイン状態ではない場合
        return redirect()->route('login');
        //ログイン画面へリダイレクト
    }
});

//認証機能に関するルートの一括生成
Auth::routes();

//productsのCRUD操作に関するルートの一括生成
Route::group(['middleware'=>'auth'],function(){
    Route::resource('products',ProductController::class);
});

