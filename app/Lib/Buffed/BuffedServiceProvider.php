<?php

namespace App\Lib\Buffed;

use App\Lib\Buffed\Buffed;
use Illuminate\Support\ServiceProvider;

class BuffedServiceProvider extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Buffed', function($app) {
	        return new Buffed();
	    });
	}

}