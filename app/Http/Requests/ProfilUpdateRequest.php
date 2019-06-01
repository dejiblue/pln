<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilUpdateRequest extends FormRequest
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

        $cpemail = $this->get('cpemail')  ;

        if ( !$cpemail ) {
            return [
                'avatar' => 'sometimes|file' , 
                'name' => 'required|string|max:255' , 
                'forname' => 'sometimes|max:255' , 
                'email' => 'required|string|email|max:255' , 
                'oldpassword' => 'sometimes|string|min:6' , 
                'password' => 'sometimes|string|min:6|confirmed' , 
                'wppassword' => 'sometimes|max:255|min:0' , 
                'wpusername' => 'sometimes|max:255|min:0' , 
                'cppassword' => 'sometimes|max:255|min:0' , 
                //API  
                'internetbskey' => 'sometimes|max:255|min:0' ,  
                'internetbspass' => 'sometimes|max:255|min:0' ,  
                'internetbs' => 'sometimes|max:255|min:0' ,  
            ];
        }

        return [
            'avatar' => 'sometimes|file' , 
            'name' => 'required|string|max:255' , 
            'forname' => 'sometimes|max:255' , 
            'email' => 'required|string|email|max:255' , 
            'oldpassword' => 'sometimes|string|min:6' , 
            'password' => 'sometimes|string|min:6|confirmed' , 
            'wppassword' => 'sometimes|max:255|min:0' , 
            'wpusername' => 'sometimes|max:255|min:0' , 
            'cppassword' => 'sometimes|max:255|min:0' , 
            'cpemail' => 'sometimes|string|email|max:255|min:0' , 

            //API  
            'internetbskey' => 'sometimes|max:255|min:0' ,  
            'internetbspass' => 'sometimes|max:255|min:0' ,  
            'internetbs' => 'sometimes|max:255|min:0' , 
            
        ];
    
    }
}
