<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MsWarehouse;
use App\Models\AccessRightPosition;

class AccessRightPositionWarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $warehouses = MsWarehouse::all();
        $accessRightPositions = AccessRightPosition::all();

        if ($warehouses->isEmpty()) {
            $this->command->warn('Tabel warehouse masih kosong! Seeder dilewati.');
            return;
        }

        if ($accessRightPositions->isEmpty()) {
            $this->command->warn('Tabel access_right_position masih kosong! Seeder dilewati.');
            return;
        }

        $data = [];
        $ordinal = 1;

        // Hubungkan setiap access_right_position dengan setiap warehouse
        foreach ($accessRightPositions as $arp) {
            foreach ($warehouses as $warehouse) {
                $data[] = [
                    'id'                       => (string) Str::uuid(),
                    'id_access_right_position' => $arp->id,
                    'id_warehouse'             => $warehouse->id, // 🌟 Diaktifkan agar terisi sesuai migrasi
                    'is_activated'             => 1,
                    'status'                   => 'terbit',
                    'ordinal'                  => $ordinal++,
                    'created_by'               => 'system',
                    'updated_by'               => 'system',
                    'created_at'               => $now,
                    'updated_at'               => $now,
                ];
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_access_right_position_warehouse')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_access_right_position_warehouse')->insert($chunk);
        }

        $this->command->info('✔ AccessRightPositionWarehouseSeeder berhasil di-seed secara terstruktur!');
    }
}
