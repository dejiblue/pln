<?php

namespace App\Providers;

use App\Categorie;
use App\Policies\CategoriePolicy;
use App\Policies\ServeurPolicy;
use App\Policies\SitePolicy;
use App\Serveur;
use App\Site;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Categorie::class => CategoriePolicy::class,
        Serveur::class => ServeurPolicy::class , 
        Site::class => SitePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
