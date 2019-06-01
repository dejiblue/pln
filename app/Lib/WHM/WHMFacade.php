<?php

namespace App\Lib\WHM;

use App\Lib\WHM\WHM;
use Illuminate\Support\Facades\Facade;

class WHMFacade extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return WHM::class;
    }

}