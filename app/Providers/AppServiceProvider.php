<?php

namespace App\Providers;

use App\Enums\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPermissionGates();
    }

    /**
     * Register authorization gates for all permissions.
     */
    protected function registerPermissionGates(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        foreach (Permission::cases() as $permission) {
            Gate::define($permission->value, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission->value, 'api');
            });
        }
    }
}
