<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class listFormRequest extends FormRequest
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
            'name' => 'bail|required|filled|string|max:32',  // 必須・文字列・２５５文字以内	
        ];
    }
}
