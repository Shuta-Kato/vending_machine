<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //ログイン・ユーザー登録系
            'user_name' => 'filled | max:255',
            'email' => 'required | max:255 | email',
            'password' => 'required | max:255 | alpha_num'
        ];
    }

    public function attributes() {
        return [
            'user_name' => 'ユーザー名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }

    public function messages() {
        return [
            'user_name.filled' => ':attributeは必須項目です。',
            'user_name.max' => ':attributeは:max字以内で入力してください。',
            'email.required' => ':attributeは必須項目です。',
            'email.max' => ':attributeは:max字以内で入力してください。',
            'email.email' => ':attributeはメールアドレス形式で入力してください。',
            'password.required' => ':attributeは必須項目です。',
            'password.max' => ':attributeは:max字以内で入力してください。',
            'password.alpha_num' => ':attributeは英数字で入力してください。',
        ];
    }
}
