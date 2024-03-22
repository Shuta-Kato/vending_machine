<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    //属性の保存、変更の設定
    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    //Productモデルとsalesテーブルのリレーション(1対多：1側)
    public function sales(){
        return $this -> hasMany(Sale::class);
    }

    //Productモデルとcompanyテーブルのリレーション(1対多：多側)
    public function company(){
        return $this -> belongsTo(Company::class);
    }
}
