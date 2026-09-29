<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Daftar posisi khusus ERP/Retail Home Appliances & System
        $positions = [
            ['name' => 'super',   'nomor' => 1],
            ['name' => 'admin',        'nomor' => 2],
            ['name' => 'developer',    'nomor' => 3],
            ['name' => 'head-office',  'nomor' => 4],
            ['name' => 'manager',      'nomor' => 5],
            ['name' => 'supervisor',   'nomor' => 6],
            ['name' => 'staff',        'nomor' => 7],
            ['name' => 'supplier',     'nomor' => 8],
            ['name' => 'system',       'nomor' => 9],
            ['name' => 'customer1',    'nomor' => 10],
            ['name' => 'customer2',    'nomor' => 11],
        ];

        // 2. Format otomatis dengan UUID dan metadata standar
        $insertData = array_map(function ($position) use ($now) {
            return array_merge($position, [
                'id'           => (string) Str::uuid(),
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'is_activated' => 1,
                'status'       => 'terbit',
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }, $positions);

        // 3. Direct Insert ke database
        DB::table('ms_positions')->insert($insertData);

        $this->command->info('✔ PositionSeeder (dengan super & retail positions) berhasil di-seed!');
    }
}
