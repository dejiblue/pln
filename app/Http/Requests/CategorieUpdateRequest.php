<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategorieUpdateRequest extends FormRequest
{

    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return response()->json( array('error'=>$errors), 200) ;
        }
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255' , 
            'color' => 'sometimes|string|max:26' , 
        ];
    }
}
