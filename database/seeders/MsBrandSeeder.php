<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MsBrandSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Umeda', 'ordinal' => 1],
            ['name' => 'Proquip', 'ordinal' => 2],
            ['name' => 'Total', 'ordinal' => 3],
            ['name' => 'Tiger', 'ordinal' => 3],
        ];

        foreach ($data as $item) {
            DB::table('ms_brands')->insert([
                'id'           => Str::uuid()->toString(),
                'name'         => $item['name'],
                'status'       => \Illuminate\Support\Arr::random(['terbit', 'draf', 'batal']),
                'ordinal'      => $item['ordinal'],
                'created_by'   => 'System',
                'updated_by'   => 'System',
                'created_at'   => now(),
                'updated_at'   => now(),
                'is_activated' => 1
            ]);
        }

        $this->command->info('Seeder Master Brand Successfully! ✅');
    }
}
