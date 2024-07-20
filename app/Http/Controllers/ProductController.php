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
        try {
            // Productモデルに基づいてクエリビルダを初期化
            $query = Product::query();
       

            // 商品名の検索キーワードがある場合、そのキーワードを含む商品をクエリに追加
            // if($search = $request->search){
            //     $query->where('product_name', 'LIKE', "%{$search}%");
            // }

            //会社名の取得
            $companies = Company::all();

            $company_id = $request->company;

            // if($company_id){ 
            //     $query->where('company_id', $company_id);
            // }

            //下限価格が指定されている場合、その価格以上の商品をクエリに追加
            // if($min_price = $request->min_price){
            //     $query->where('price','>=',$min_price);
            // }

            // //上限価格が指定されている場合、その価格以下の商品をクエリに追加
            // if($max_price = $request->max_price){
            //     $query->where('price','<=',$max_price);
            // }

            // //下限在庫が指定されている場合、その価格以上の商品をクエリに追加
            // if($min_stock = $request->min_stock){
            //     $query->where('stock','>=',$min_stock);
            // }

            // //上限在庫が指定されている場合、その価格以下の商品をクエリに追加
            // if($max_stock = $request->max_stock){
            //     $query->where('stock','<=',$max_stock);
            // }

            //ソートのパラメータが指定されている場合、そのカラムでソートを行う
            if($sort = $request->sort){
                $direction = $request->direction == 'desc' ? 'desc' : 'asc';

                //ソートするカラムがcompany_nameかどうかを確認する
                if($sort === 'company_name'){
                    //companiesテーブルと結合し、company_nameでソート
                    $query->join('companies', 'products.company_id', '=', 'companies.id')
                    ->select('products.*', 'companies.company_name')
                    ->orderBy('companies.company_name', $direction);
                }else{
                //他のカラムでソート
                $query->orderBy($sort, $direction);
            }
        }
        
            // 上記の条件(クエリ）に基づいて商品を取得し、10件ごとのページネーションを適用
            $products = $query->paginate(10);
        
            // 商品一覧ビューを表示し、取得した商品情報をビューに渡す
            return view('products.index', ['products' => $products, 'companies' => $companies]);
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return back()->withError($e->getMessage())->withInput();
        }
    }
    

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            //〇商品追加画面
            //会社情報の取得
            $companies = Company::all();
            //create.blade.phpの表示
            return view('products.create',compact('companies'));
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return back()->withError($e->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            //show.blade.phpの表示
            return view('products.show',['product' => $product]);
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        try {
            //会社情報の取得
            $companies = Company::all();
            //edit.blade.phpの表示
            return view('products.edit',compact('product','companies'));
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return back()->withError($e->getMessage())->withInput();
        }
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
    try {
        // 必要な情報が全てそろっているかのチェック
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'company_id' => 'required',
        ]);

        // 商品情報の更新
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;
        $product->company_id = $request->company_id;

        // 新しい画像がアップロードされた場合の処理
        if ($request->hasFile('img_path')) {
            // 画像のアップロード処理
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            // 保存した画像のパスをデータベースに保存
            $product->img_path = '/storage/' . $filePath;
        }

        // 更新した情報の保存
        $product->save();

        // 処理後、商品一覧画面へリダイレクト（ビュー画面へ商品情報更新できた旨メッセージが出るようにする）
        return redirect()->route('products.index')->with('success', '商品情報の変更が完了しました。');
    } catch (\Exception $e) {
        // エラーが発生した場合の処理
        return back()->withError($e->getMessage())->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            // 商品に関連するsalesデータを削除
            $product->sales()->delete(); 
    
            // 商品を削除
            $product->delete();
    
            // JSONレスポンスを返す
            return response()->json(['success' => '商品が削除されました。']);
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    //検索機能の非同期処理化のためにserchメソッドを追加
    public function search(Request $request, Product $product)
    {
        \Log::info('Search method called.');
        try {
            
            $companies = Company::all();
            $company_id = $request->company;
            $query = Product::query();
            
            // 検索条件の適用
            if ($search = $request->input('search')) {
                $query->where('product_name', 'LIKE', "%{$search}%");
            }
    
            if ($company_id = $request->input('company')) {
                $query->where('company_id', $company_id);
            }
    
            if ($min_price = $request->input('min_price')) {
                $query->where('price', '>=', $min_price);
            }
    
            if ($max_price = $request->input('max_price')) {
                $query->where('price', '<=', $max_price);
            }
    
            if ($min_stock = $request->input('min_stock')) {
                $query->where('stock', '>=', $min_stock);
            }
    
            if ($max_stock = $request->input('max_stock')) {
                $query->where('stock', '<=', $max_stock);
            }
    
            // 関連する会社情報をロードし、ページネーションを適用して10件ごとに結果を取得
            $products = $query->with('company')->paginate(10);
    
            // JSONで結果を返す
            return response()->json(['products' => $products]);
    
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    }