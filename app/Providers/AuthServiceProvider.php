<?php

namespace App\Providers;


use App\Menu;
use App\Permission;
use App\Policies\ArticlePolicy;
use App\Policies\MenusPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PortfolioPolicy;
use App\Portfolio;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use App\Article;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Permission::class => PermissionPolicy::class,
        Menu::class => MenusPolicy::class,
        Portfolio::class => PortfolioPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('VIEW_ADMIN', function ($user){
            return $user->canDo('VIEW_ADMIN', true);
        });
        Gate::define('VIEW_ADMIN_ARTICLES', function ($user){
            return $user->canDo('VIEW_ADMIN_ARTICLES', true);
        });
        Gate::define('EDIT_USERS', function ($user){
            return $user->canDo('EDIT_USERS', true);
        });
        Gate::define('VIEW_ADMIN_MENU', function ($user){
            return $user->canDo('VIEW_ADMIN_MENU', true);
        });

    }
}
