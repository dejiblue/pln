<?php

namespace App\Lib\TOOLS;

use App\Lib\TOOLS\TOOLS;
use Illuminate\Support\Facades\Facade;

class TOOLSFacade extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return TOOLS::class;
    }

}