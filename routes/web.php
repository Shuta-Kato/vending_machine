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


Route::middleware('auth')->group(function () {
    // 商品一覧を表示するためのルート
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 商品作成フォームを表示するためのルート
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    // 商品を作成するためのルート
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // 特定の商品の詳細を表示するためのルート
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // 商品編集フォームを表示するためのルート
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // 商品を更新するためのルート
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // 商品を削除するためのルート
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


