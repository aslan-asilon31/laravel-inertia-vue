<?php

namespace App\Helpers\FormHook\Traits;

use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\AccessRight;

trait CheckAccessRight
{
    /**
     * Check if the logged-in employee has the required permission.
     * Throws a 403 error automatically if unauthorized.
     */
    public function checkAccessRight(string $permissionName): void
    {
        $permission = AccessRight::where('name', $permissionName)->first();

        // dd($permission, $permissionName);
        if (!$permission) {
            abort(403, 'Cannot Access');
        }

        Gate::authorize('hasAccess', $permission);
    }
}
