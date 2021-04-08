<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Log;

class RegisterFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
			'id' => 'bail|required|regex:/^[a-zA-Z0-9_]+$/|max:32|unique:users,id',  // 必須・文字列・２５５文字以内
			'email' => 'bail|required|email|max:256|unique:users,e_mail',		// 必須
			'password' => 'bail|required|max:256',          // 必須・整数		
			'name' => 'bail|required|string|max:32',          // 必須・整数		
        ];
    }
}
