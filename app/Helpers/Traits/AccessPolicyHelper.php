<?php

namespace App\Helpers\Traits;

use App\Models\MsEmployeeAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait AccessPolicyHelper
{
    protected static array $positionCache = [];

    /**
     * Resolusi ID Employee dari parameter, Guard 'employee', atau Session aktif.
     */
    public function resolveUserId($employee): ?string
    {
        // Strictly using guard('employee'), avoiding Auth::user()
        $user = $employee ?? Auth::guard('employee')->user();

        if (!$user) {
            $sesid = request()->input('sesid') ?? request()->query('sesid');

            if ($sesid) {
                $employeeSession = DB::table('ms_employee_sessions')
                    ->where('sesid', $sesid)
                    ->where('is_logged', true)
                    ->first();

                if ($employeeSession) {
                    $user = MsEmployeeAccount::where('id_employee', $employeeSession->id_employee)->first();

                    if ($user) {
                        Auth::guard('employee')->setUser($user);
                    }
                }
            }
        }

        if (!$user) {
            return null;
        }

        return $user->id_employee ?? $user->id ?? null;
    }

    /**
     * Cek apakah employee memiliki jabatan super, Developer, atau Admin.
     */
    public function isAdminOrSuper(string $employeeId): bool
    {
        if (isset(self::$positionCache[$employeeId]['is_admin'])) {
            return self::$positionCache[$employeeId]['is_admin'];
        }

        $user = Auth::guard('employee')->user();
        if ($user && isset($user->position?->name)) {
            $directLower = strtolower($user->position->name);
            if (str_contains($directLower, 'super') || str_contains($directLower, 'developer') || str_contains($directLower, 'admin')) {
                return self::$positionCache[$employeeId]['is_admin'] = true;
            }
        }

        $isAdmin = DB::table('pv_employee_position as ep')
            ->join('ms_positions as p', 'ep.id_position', '=', 'p.id')
            ->where('ep.id_employee', $employeeId)
            ->where('ep.is_activated', 1)
            ->where(function ($q) {
                $q->where('p.name', 'like', '%super%')
                    ->orWhere('p.name', 'like', '%admin%');
            })
            ->exists();

        return self::$positionCache[$employeeId]['is_admin'] = $isAdmin;
    }

    /**
     * Mengambil ID Position aktif milik employee.
     */
    public function getActivePositionIds(string $employeeId): array
    {
        if (isset(self::$positionCache[$employeeId]['ids'])) {
            return self::$positionCache[$employeeId]['ids'];
        }

        return self::$positionCache[$employeeId]['ids'] = DB::table('pv_employee_position')
            ->where('id_employee', $employeeId)
            ->where('is_activated', 1)
            ->pluck('id_position')
            ->toArray();
    }

    /**
     * Helper parsing permission.
     */
    public function parsePermission(string|object $permission): array
    {
        return [
            'name' => is_object($permission) ? ($permission->name ?? $permission->id) : $permission,
            'id'   => is_object($permission) ? ($permission->id ?? null) : $permission,
        ];
    }
}
