<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MsPosition;
use App\Models\AccessRight;

class AccessRightPositionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $positions = MsPosition::all()->keyBy('name');
        $allAccessRights = AccessRight::all();

        if ($allAccessRights->isEmpty() || $positions->isEmpty()) {
            $this->command->warn('Data Position atau AccessRight kosong. Seeder dilewati.');
            return;
        }

        $data = [];
        $existingKeys = [];
        $ordinal = 1;

        $addAccess = function ($positionId, $accessRightId) use (&$data, &$existingKeys, &$ordinal, $now) {
            $comboKey = "{$positionId}-{$accessRightId}";
            if (!isset($existingKeys[$comboKey])) {
                $existingKeys[$comboKey] = true;
                $data[] = [
                    'id'               => (string) Str::uuid(),
                    'id_position'      => $positionId,
                    'id_access_right'  => $accessRightId,
                    'created_by'       => 'system',
                    'updated_by'       => 'system',
                    'ordinal'          => $ordinal++,
                    'status'           => 'terbit',
                    'is_activated'     => 1,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
        };

        // 1. super & DEVELOPER
        foreach (['super', 'developer'] as $fullRole) {
            if (isset($positions[$fullRole])) {
                $posId = $positions[$fullRole]->id;
                foreach ($allAccessRights as $access) {
                    $addAccess($posId, $access->id);
                }
            }
        }

        // 2. ADMIN
        if (isset($positions['admin'])) {
            $adminId = $positions['admin']->id;
            $adminAccesses = AccessRight::where('name', 'NOT LIKE', 'permission%')
                ->where('name', 'NOT LIKE', 'hak_akses%')
                ->get();

            foreach ($adminAccesses as $access) {
                $addAccess($adminId, $access->id);
            }
        }

        // 3. MANAGER & HEAD-OFFICE
        foreach (['manager', 'head-office'] as $mgrRole) {
            if (isset($positions[$mgrRole])) {
                $mgrId = $positions[$mgrRole]->id;
                $mgrAccesses = AccessRight::where('name', 'LIKE', 'tr_%')
                    ->orWhere('name', 'LIKE', 'rp_%')
                    ->orWhere('name', 'LIKE', 'dashboard%')
                    ->orWhere('name', 'LIKE', 'profile%')
                    ->get();

                foreach ($mgrAccesses as $access) {
                    $addAccess($mgrId, $access->id);
                }
            }
        }

        // 4. STAFF
        if (isset($positions['staff'])) {
            $staffId = $positions['staff']->id;
            $staffAccesses = AccessRight::where('name', 'LIKE', 'ms_%')
                ->orWhere('name', 'LIKE', 'tr_%')
                ->orWhere('name', 'LIKE', 'dashboard%')
                ->orWhere('name', 'LIKE', 'profile%')
                ->get();

            foreach ($staffAccesses as $access) {
                $addAccess($staffId, $access->id);
            }
        }

        // 5. SUPPLIER
        if (isset($positions['supplier'])) {
            $supplierId = $positions['supplier']->id;
            $supplierRights = [
                'dashboard-list',
                'profile-list',
                'profile-create',
                'profile-store',
                'profile-edit',
                'profile-update',
                'profile_detail-list',
                'profile_detail-edit',
                'tr_purchase_request-list',
                'tr_purchase_request-create',
                'tr_purchase_request-edit',
                'tr_purchase_request-update',
                'tr_purchase_request-show',
                'tr_purchase_request_detail-list',
                'tr_purchase_request_detail-create',
                'tr_purchase_request_detail-store',
                'tr_purchase_request_detail-update',
                'tr_purchase_request_detail-edit',
                'tr_purchase_request_detail-show',
                'tr_purchase_order-list',
                'tr_purchase_order-create',
                'tr_purchase_order-edit',
                'tr_purchase_order-update',
                'tr_purchase_order-show',
                'tr_purchase_order_detail-list',
                'tr_purchase_order_detail-create',
                'tr_purchase_order_detail-store',
                'tr_purchase_order_detail-update',
                'tr_purchase_order_detail-edit',
                'tr_purchase_order_detail-show',
                'tr_purchase_return-list',
                'tr_purchase_return-create',
                'tr_purchase_return-edit',
                'tr_purchase_return-update',
                'tr_purchase_return-show',
                'tr_purchase_return_detail-list',
                'tr_purchase_return_detail-create',
                'tr_purchase_return_detail-store',
                'tr_purchase_return_detail-update',
                'tr_purchase_return_detail-edit',
                'tr_purchase_return_detail-show',
            ];

            $supplierAccesses = AccessRight::whereIn('name', $supplierRights)->get();
            foreach ($supplierAccesses as $access) {
                $addAccess($supplierId, $access->id);
            }
        }

        // 6. DASHBOARD DEFAULT
        $dashboardAccess = AccessRight::where('name', 'dashboard-list')->first();
        if ($dashboardAccess) {
            foreach ($positions as $position) {
                $addAccess($position->id, $dashboardAccess->id);
            }
        }

        // TRUNCATE & BULK INSERT
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_access_right_position')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_access_right_position')->insert($chunk);
        }

        $this->command->info('✔ Dynamic AccessRightPositionSeeder berhasil disinkronkan!');
    }
}
