<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MsWarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = ['DC05', 'DC89', 'DCSMG', 'DCSBY', 'DCBDG', 'DCMDN'];
        $now = now();

        foreach ($warehouses as $index => $name) {
            DB::table('ms_warehouses')->insert([
                'id'           => Str::uuid()->toString(),
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => 'System',
                'updated_by'   => 'System',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1
            ]);
        }
    }
}
