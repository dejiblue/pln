<?php

namespace App\Lib\WP;

use App\Lib\WP\WP;
use Illuminate\Support\Facades\Facade;

class WPFacade extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return WP::class;
    }

}