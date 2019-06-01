<?php

namespace App\Lib\SSH;

use App\Lib\SSH\SSH;
use Illuminate\Support\ServiceProvider;

class SSHServiceProvider extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('SSH', function($app) {
	        return new SSH();
	    });
	}

}