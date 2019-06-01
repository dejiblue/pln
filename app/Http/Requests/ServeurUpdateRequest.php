<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServeurUpdateRequest extends FormRequest
{
    
    public function expectsJson()
    {
        return true;
    }

    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'name'      => 'sometimes|string|max:255' , 
            'url'       => 'sometimes|url' , 
            'port'      => 'sometimes|int' , 
            'accesstoken'     => 'sometimes|string',
            'username'     => 'sometimes|string',
        ];
    }
}
