<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
			'id' => 'bail|required|regex:/^[a-zA-Z0-9_]+$/|exists:users,id',  // 必須・文字列・２５５文字以内
			'password' => 'bail|required|max:256',          // 必須・整数		
        ];
    }
}
