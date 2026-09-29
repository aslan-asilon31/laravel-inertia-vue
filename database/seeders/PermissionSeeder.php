<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MsPosition;
use App\Models\MsPage;
use App\Models\MsAction;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pages = MsPage::where('is_activated', true)->get();
        $actions = MsAction::where('is_activated', true)->get();
        $positions = MsPosition::all();

        if ($pages->isEmpty() || $actions->isEmpty() || $positions->isEmpty()) {
            $this->command->warn('Data master Page, Action, atau Position belum lengkap.');
            return;
        }

        $now = Carbon::now();
        $allPermissionIds = [];
        $dashboardPermissionIds = [];

        // 1. Ambil existing permissions dari DB ke memori untuk menghindari query exists() di dalam loop
        $existingPermissions = Permission::pluck('id')->flip()->toArray();

        // 2. Ambil nilai max ordinal SEKALI SAJA di awal
        $currentPermissionOrdinal = Permission::max('ordinal') ?? 0;
        $currentPivotOrdinal = DB::table('position_permission')->max('ordinal') ?? 0;

        $insertPermissions = [];

        // --- GENERATE DATA MASTER PERMISSION ---
        foreach ($pages as $page) {
            foreach ($actions as $action) {
                $id = Str::snake(strtolower("{$page->name}-{$action->name}"));
                $allPermissionIds[] = $id;

                if (strtolower($page->name) === 'dashboard') {
                    $dashboardPermissionIds[] = $id;
                }

                // Cek dari memori, jika sudah ada lewati
                if (isset($existingPermissions[$id])) {
                    continue;
                }

                $currentPermissionOrdinal++;

                $insertPermissions[] = [
                    'id'           => $id,
                    'ms_page_id'   => $page->id,
                    'ms_action_id' => $action->id,
                    'name'         => $page->name . ' (' . $action->name . ')',
                    'ordinal'      => $currentPermissionOrdinal,
                    'created_by'   => 'system',
                    'updated_by'   => 'system',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'is_activated' => true,
                ];

                // Tandai agar tidak duplikat di memori yang sama
                $existingPermissions[$id] = true;
            }
        }

        // Bulk Insert Permissions baru
        if (!empty($insertPermissions)) {
            foreach (array_chunk($insertPermissions, 1000) as $chunk) {
                DB::table('permissions')->insert($chunk);
            }
        }

        // --- LOGIKA ASSIGN HAK AKSES BERDASARKAN POSITION ---
        $allPivotData = [];

        // Ambil semua posisi ID untuk menghapus pivot lama sekaligus (lebih bersih daripada di dalam loop posisi)
        $positionIds = $positions->pluck('id')->toArray();
        if (!empty($positionIds)) {
            DB::table('position_permission')->whereIn('id_position', $positionIds)->delete();
        }

        foreach ($positions as $position) {
            $assignedPermissions = [];

            if (strtolower($position->name) === 'admin') {
                $assignedPermissions = $allPermissionIds;
            } else {
                $assignedPermissions = $dashboardPermissionIds;
                $nonDashboardPermissions = array_diff($allPermissionIds, $dashboardPermissionIds);

                if (!empty($nonDashboardPermissions)) {
                    $randomKeys = (array) array_rand($nonDashboardPermissions, min(rand(5, 15), count($nonDashboardPermissions)));
                    foreach ($randomKeys as $key) {
                        $assignedPermissions[] = $nonDashboardPermissions[$key];
                    }
                }
            }

            foreach ($assignedPermissions as $permId) {
                $currentPivotOrdinal++;
                $allPivotData[] = [
                    'id'             => (string) Str::uuid(),
                    'id_position' => $position->id,
                    'permission_id'  => $permId,
                    'ordinal'        => $currentPivotOrdinal,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
        }

        // Bulk Insert Pivot Data secara massal
        if (!empty($allPivotData)) {
            foreach (array_chunk($allPivotData, 1000) as $chunk) {
                DB::table('position_permission')->insert($chunk);
            }
        }

        $this->command->info('PermissionSeeder Berhasil Dijalankan dengan Super Cepat! 🚀');
    }
}
