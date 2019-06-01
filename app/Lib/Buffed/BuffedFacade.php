<?php

namespace App\Lib\Buffed;

use App\Lib\Buffed\Buffed;
use Illuminate\Support\Facades\Facade;

class BuffedFacade extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return Buffed::class;
    }

}