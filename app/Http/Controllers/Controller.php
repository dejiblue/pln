<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successJson( $resp )
    {
    	return response()->json( array('success'=>$resp), 200);
    }

    public function errorJson( $resp )
    {
    	return response()->json( array('error'=>$resp), 200);
    }

}
//