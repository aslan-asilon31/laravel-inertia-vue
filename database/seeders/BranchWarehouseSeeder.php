<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BranchWarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil data dari tabel cabang dan gudang jika tabelnya ada
        $branches = Schema::hasTable('ms_branches') ? DB::table('ms_branches')->get() : collect();
        $warehouses = Schema::hasTable('ms_warehouses') ? DB::table('ms_warehouses')->get() : collect();

        $data = [];
        $ordinal = 1;

        // 2. Jika data master cabang & gudang tersedia, buat kombinasi relasinya
        if ($branches->isNotEmpty() && $warehouses->isNotEmpty()) {
            foreach ($branches as $branch) {
                foreach ($warehouses as $warehouse) {
                    $data[] = [
                        'id'                  => (string) Str::uuid(),
                        'id_branch'        => $branch->id ?? null,
                        'name_branch'      => $branch->name ?? 'Cabang Utama',
                        'id_warehouse'     => $warehouse->id ?? null,
                        'name_warehouse'   => $warehouse->name ?? 'Gudang Utama',
                        'ordinal'             => $ordinal++,
                        'status'              => 'terbit',
                        'created_by'          => 'system',
                        'updated_by'          => 'system',
                        'created_at'          => $now,
                        'updated_at'          => $now,
                        'is_activated'        => 1,
                    ];
                }
            }
        } else {
            // 3. Fallback data dummy jika tabel master belum ada / kosong
            $defaultData = [
                ['branch' => 'Cabang Jakarta Pusat', 'warehouse' => 'Gudang Logistik Pusat'],
                ['branch' => 'Cabang Surabaya', 'warehouse' => 'Gudang Regional Timur'],
                ['branch' => 'Cabang Bandung', 'warehouse' => 'Gudang Transit Parahyangan'],
            ];

            foreach ($defaultData as $item) {
                $data[] = [
                    'id'                  => (string) Str::uuid(),
                    'id_branch'        => (string) Str::uuid(),
                    'name_branch'      => $item['branch'],
                    'id_warehouse'     => (string) Str::uuid(),
                    'name_warehouse'   => $item['warehouse'],
                    'ordinal'             => $ordinal++,
                    'status'              => 'terbit',
                    'created_by'          => 'system',
                    'updated_by'          => 'system',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                    'is_activated'        => 1,
                ];
            }
        }

        // 4. Bersihkan tabel dan masukkan data secara aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_branch_warehouses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_branch_warehouses')->insert($chunk);
        }

        $this->command->info('✔ BranchWarehouseSeeder berhasil dijalankan dengan sukses!');
    }
}
