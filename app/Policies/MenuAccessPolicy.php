<?php

namespace App\Policies;

use App\Models\MsEmployeeAccount;
use Illuminate\Support\Facades\DB;
use App\Helpers\Traits\AccessPolicyHelper;

class MenuAccessPolicy
{
    use AccessPolicyHelper;

    public function canShowMenu(?MsEmployeeAccount $employeeAccount, string $menuPermission): bool
    {
        $employeeAccountId = $this->resolveUserId($employeeAccount);
        if (!$employeeAccountId) return false;

        if ($this->isAdminOrSuper($employeeAccountId)) return true;

        $positionIds = $this->getActivePositionIds($employeeAccountId);
        if (empty($positionIds)) return false;

        return DB::table('pv_access_right_position as arp')
            ->join('access_right as ar', 'arp.id_access_right', '=', 'ar.id')
            ->whereIn('arp.id_position', $positionIds)
            ->where('ar.name', $menuPermission)
            ->exists();
    }
}
