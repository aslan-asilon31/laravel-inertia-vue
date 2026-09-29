<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccessRightPositionWarehouseBranchSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil seluruh data dari tabel parent (access_right_position_warehouse)
        $parentRecords = DB::table('pv_access_right_position_warehouse')->get();

        if ($parentRecords->isEmpty()) {
            $this->command->warn('Tabel access_right_position_warehouse masih kosong! Seeder dilewati.');
            return;
        }

        // Ambil referensi data cabang dari tabel master (misal: pv_branch_warehouses atau ms_branches)
        $branches = DB::table('pv_branch_warehouses')->get(); // Sesuaikan dengan tabel master cabang Anda jika ada

        $data = [];
        $ordinal = 1;

        // 2. Hubungkan setiap record dari parent warehouse dengan cabang
        foreach ($parentRecords as $parent) {
            // Jika ada tabel master cabang, kita bisa loop atau ambil relasinya. 
            // Jika berdiri sendiri, kita isi dengan nilai default/null atau ambil acak dari referensi cabang.
            $branchId   = $branches->isNotEmpty() ? $branches->random()->id_branch ?? null : null;
            $branchName = $branches->isNotEmpty() ? $branches->random()->name_branch ?? 'Cabang Utama' : 'Cabang Utama';

            $data[] = [
                'id'                                 => (string) Str::uuid(),
                'id_access_right_position_warehouse' => $parent->id,
                'id_branch'                          => $branchId,
                'name_branch'                        => $branchName,
                'is_activated'                       => 1,
                'status'                             => 'terbit',
                'ordinal'                            => $ordinal++,
                'created_by'                         => 'system',
                'updated_by'                         => 'system',
                'created_at'                         => $now,
                'updated_at'                         => $now,
            ];
        }

        // 3. Reset Foreign Key & Truncate Tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_access_right_position_warehouse_branch')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Bulk Insert dengan Chunking
        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_access_right_position_warehouse_branch')->insert($chunk);
        }

        $this->command->info('✔ AccessRightPositionWarehouseBranchSeeder berhasil di-seed secara terstruktur!');
    }
}
