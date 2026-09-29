<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\MsEmployeeAccount;
use App\Models\AccessRight;
use App\Models\Permission;
use App\Policies\AccessRightPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(AccessRight::class, AccessRightPolicy::class);
        Gate::policy(Permission::class, AccessRightPolicy::class);

        Gate::define('hasAccess', function ($user, string|object|null $permission = null) {
            if (!$permission) {
                return false;
            }
            return app(AccessRightPolicy::class)->hasAccess(
                $user instanceof MsEmployeeAccount ? $user : null,
                $permission
            );
        });
    }
}
