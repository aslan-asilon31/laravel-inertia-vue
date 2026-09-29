<?php

namespace App\Policies;

use Illuminate\Support\Facades\DB;
use App\Helpers\Traits\AccessPolicyHelper;
use Illuminate\Support\Facades\Auth;

class ColumnAccessPolicy
{
    use AccessPolicyHelper;

    public function punyaAksesKolom($karyawan, string|object $hakAkses): bool
    {
        $employeeId = $this->resolveUserId($karyawan);

        // dd([
        //     'employee_id' => $employeeId,
        //     'is_admin' => $this->isAdminOrSuper($employeeId),
        //     'position_ids' => $this->getActivePositionIds($employeeId),
        //     'user_auth' => Auth::guard('employee')->user()?->toArray()
        // ]);

        if (!$employeeId) {
            return true;
        }

        if ($this->isAdminOrSuper($employeeId)) return true;

        $positionIds = $this->getActivePositionIds($employeeId);
        if (empty($positionIds)) return true;

        ['name' => $hakAksesName] = $this->parsePermission($hakAkses);
        if (empty($hakAksesName)) return true;

        $dibatasi = DB::table('pv_access_right_position as arp')
            ->join('access_right as ar', 'arp.id_access_right', '=', 'ar.id')
            ->whereIn('arp.id_position', $positionIds)
            ->where('ar.name', $hakAksesName)
            ->exists();

        return !$dibatasi;
    }
}
