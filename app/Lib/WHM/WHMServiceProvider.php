<?php

namespace App\Lib\WHM;

use App\Lib\WHM\WHM;
use Illuminate\Support\ServiceProvider;

class WHMServiceProvider extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('WHM', function($app) {
	        return new WHM();
	    });
	}

}