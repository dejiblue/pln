<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
            'attachable_id' => 'required|int' , 
            'file' => 'required' , 
            'attachable_type' => 'required' , 
        ];
    }

}
