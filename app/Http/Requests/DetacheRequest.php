<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetacheRequest extends FormRequest
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
            'attache_id' => 'required|int' , 
        ];
    }

}
