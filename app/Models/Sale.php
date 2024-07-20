<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['product_id',];

    //Saleモデルとproductsテーブルのリレーション(1対多：多側)
    public function product(){
        return $this -> belongsTo(Product::class);
    }

}
