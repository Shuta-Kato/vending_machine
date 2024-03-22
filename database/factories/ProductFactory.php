<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            //ダミーの商品名を生成
            'product_name' => $this->faker->word,
            //100から10000までの範囲でランダムなダミー価格を生成
            'price' => $this->faker->numberBetween(100,10000),
            //0から9までのランダムな数字でダミー在庫数を生成
            'stock' => $this->faker->randomDigit,
            //ダミーの説明文を生成
            'comment' => $this->faker->sentence,
            //200x300のランダムな画像を呼び出す
            'img_path' => 'https://picsum.photos/200/300',
        ];
    }
}
