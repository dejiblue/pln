<?php

namespace App\Lib\WP;

use App\Lib\WP\WP;
use Illuminate\Support\ServiceProvider;

class WPServiceProvider extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('WP', function($app) {
	        return new WP();
	    });
	}

}