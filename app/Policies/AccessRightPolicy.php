<?php

namespace App\Policies;

use App\Models\MsEmployeeAccount;
use Illuminate\Support\Facades\DB;
use App\Helpers\Traits\AccessPolicyHelper;

class AccessRightPolicy
{
    // fokus murni menangani tombol, halaman, dan hak akses CRUD utama.
    use AccessPolicyHelper;

    protected static array $accessCache = [];

    public function hasAccess(?MsEmployeeAccount $employeeAccount, string|object $permission): bool
    {
        $employeeAccountId = $this->resolveUserId($employeeAccount);
        if (!$employeeAccountId) {
            return false;
        }

        // Bypass untuk super / Admin / Developer
        if ($this->isAdminOrSuper($employeeAccountId)) {
            return true;
        }

        $positionIds = $this->getActivePositionIds($employeeAccountId);
        if (empty($positionIds)) {
            return false;
        }

        ['name' => $permissionName, 'id' => $permissionId] = $this->parsePermission($permission);
        if (empty($permissionName)) {
            return false;
        }

        $cacheKey = "page_{$employeeAccountId}_{$permissionName}_{$permissionId}";
        if (isset(self::$accessCache[$cacheKey])) {
            return self::$accessCache[$cacheKey];
        }

        $hasAccess = DB::table('pv_access_right_position as arp')
            ->join('access_right as ar', 'arp.id_access_right', '=', 'ar.id')
            ->whereIn('arp.id_position', $positionIds)
            ->where(function ($query) use ($permissionName, $permissionId) {
                $query->where('ar.name', $permissionName)
                    ->orWhere('ar.id', $permissionId);
            })
            ->exists();

        return self::$accessCache[$cacheKey] = $hasAccess;
    }

    // 🌟 Semua method mapping di bawah juga disesuaikan ke MsEmployeeAccount
    public function list(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function index(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function view(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function create(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function edit(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function update(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
    public function delete(?MsEmployeeAccount $employeeAccount, $permission): bool
    {
        return $this->hasAccess($employeeAccount, $permission);
    }
}
