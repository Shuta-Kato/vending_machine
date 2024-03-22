<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Productモデルに基づいてクエリビルダを初期化
        $query = Product::query();
   

        // 商品名の検索キーワードがある場合、そのキーワードを含む商品をクエリに追加
        if($search = $request->search){
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        //会社名の取得
        $companies = Company::all();

        $company_id = $request->company;

        if($company_id){ 
            $query->where('company_id', $company_id);
        }
        
    
        // 上記の条件(クエリ）に基づいて商品を取得し、10件ごとのページネーションを適用
        $products = $query->paginate(10);
    
        // 商品一覧ビューを表示し、取得した商品情報をビューに渡す
        return view('products.index', ['products' => $products, 'companies' => $companies]);
    }
    

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //〇商品追加画面
        //会社情報の取得
        $companies = Company::all();
        //create.blade.phpの表示
        return view('products.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーションの設定
        $request -> validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        //商品の作成
        //↓新しいインスタンス「Product」（レコード）の作成
        $product = new Product([
            'product_name' => $request -> get('product_name'),
            'company_id' => $request -> get('company_id'),
            'price' => $request -> get('price'),
            'stock' => $request -> get('stock'),
            'comment' => $request -> get('comment'),
        ]);

        //画像の保存
        if($request->hasFile('img_path')){
            //アップロードした画像のファイル名を取得
            $filename = $request -> img_path -> getClientOriginalName();
            //アップロードされたファイルを指定場所に保存
            $filePath = $request -> img_path -> storeAs('products',$filename,'public');
            $product -> img_path = '/storage/' .$filePath;
        }
        //作成したデータベースに新しいレコードとして保存
        $product -> save();

        //処理後、商品一覧画面に戻る
        return redirect('products');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //show.blade.phpの表示
        return view('products.show',['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //会社情報の取得
        $companies = Company::all();
        //edit.blade.phpの表示
        return view('products.edit',compact('product','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //必要な情報が全てそろっているかのチェック
        $request -> validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        //商品情報の更新
        $product -> product_name = $request -> product_name;
        $product -> price = $request -> price;
        $product -> stock = $request -> stock;

        // 新しい画像がアップロードされた場合の処理
        if ($request->hasFile('img_path')) {
            // 画像のアップロード処理
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            // 保存した画像のパスをデータベースに保存
            $product->img_path = '/storage/' . $filePath;
        }
        

        //更新した情報の保存
        $product -> save();

        //処理後、商品一覧画面へ戻る（ビュー画面へ商品情報更新できた旨メッセージが出るようにする）
        return redirect() -> route('products.index') ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //商品を削除
        $product -> delete();

        //処理後、商品一覧画面へ戻る
        return redirect('/products');
    }
}
