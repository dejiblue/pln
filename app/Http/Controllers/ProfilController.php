<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProfilUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function update( ProfilUpdateRequest $request )
    {
    		
    	$user = Auth::user();
 		$update = array() ;  

    	if ( $request->get('scriptfile') ) {
            $update['scriptfile'] = $request->get('scriptfile') ; 
    		$update['script'] = $request->get('script') ; 
        }else if ( $script = $request->get('script') !== null ) {
           $update['script'] = $script;
        }

    	$pass = $request->only('oldpassword','password') ;

        if ( !empty($pass['password']) && empty($pass['oldpassword']) ) {
            return $this->errorJson(array('oldpassword'=>true));
        } else if (!empty($pass['oldpassword'])&&!empty($pass['password'])) {
    		if ( !Hash::check($pass['oldpassword'], $user->password) ) {
    			return $this->errorJson(array('oldpassword'=>true));
    		}
    		$update['password'] = Hash::make($pass['password']) ;
    	}

    	$last = $request->only('internetbs','internetbspass','internetbskey','name','forname','email','wppassword','cppassword','wpusername','cpemail' ) ; 

        if ( isset($last['internetbs']) && $last['internetbs'] ) {
            $last['internetbs'] = true ;
        }else{
            $last['internetbs'] = false ;
        }

    	$allupdate = array_merge( $last , $update ) ; 

        $newuser = $user->update( $allupdate ) ; 

    	return $this->successJson($newuser);

    }

}
