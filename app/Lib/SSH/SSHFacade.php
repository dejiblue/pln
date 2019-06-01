<?php

namespace App\Lib\SSH;

use App\Lib\SSH\SSH;
use Illuminate\Support\Facades\Facade;

class SSHFacade extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return SSH::class;
    }

}