<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AppController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request )
    {
        return view('app');
    }

    public function passCheck( Request $request )
    {

    	$pass = $request->get('pass') ; 
    	
    	$password = $pass; 
    	$retval = '' ; 
		$last_line = array();
		exec( 'cpapi2 PasswdStrength get_password_strength --output=json '.http_build_query(array('password'=>$password),'',' ') , $last_line ,$retval );
		$res = json_decode(implode('', $last_line),true) ; 
		if ( isset($res['cpanelresult']['data'][0])) {
			return response()->json(  $res['cpanelresult']['data'][0] , 200) ;
		}

		return response()->json( array('strength'=>100) , 200) ;

    }

    public function ipslog( Request $request )
    {

        $user = Auth::user() ;  
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR );
        return is_file( $path.'ips.log' )? preg_replace("/\r\n|\r|\n/",'<br/>', file_get_contents( $path.'ips.log' ) ) : '' ;

    }

    public function internetbslog( Request $request )
    {

        $user = Auth::user() ;  
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR );
        return is_file( $path.'internetbs.log' )? preg_replace("/\r\n|\r|\n/",'<br/>', file_get_contents( $path.'internetbs.log' ) ) : '' ; 

    }

    public function runscript(  Request $request  )
    {
        $user = Auth::user() ;  
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR );
        return is_file( $path.'runscript.log' )? preg_replace("/\r\n|\r|\n/",'<br/>', file_get_contents( $path.'runscript.log' ) ) : '' ; 
    }


}
