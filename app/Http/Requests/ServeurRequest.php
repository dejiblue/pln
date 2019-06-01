<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServeurRequest extends FormRequest
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
            'name'      => 'required|string|max:255' , 
            'url'       => 'required|url' , 
            'port'      => 'required|int' , 
            'accesstoken'     => 'required|string' ,
            'username'     => 'required|string|max:255' ,
            'sshport'     => 'required|int' 
        ];
        
    }

}
