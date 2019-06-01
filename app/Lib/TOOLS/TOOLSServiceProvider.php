<?php

namespace App\Lib\TOOLS;

use App\Lib\TOOLS\TOOLS;
use Illuminate\Support\ServiceProvider;

class TOOLSServiceProvider extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('TOOLS', function($app) {
	        return new TOOLS();
	    });
	}

}