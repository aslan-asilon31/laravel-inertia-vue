<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MsProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Premium Series',
            'Standard Series',
            'Compact Edition',
            'Pro Version',
            'Lite Version',
            'Limited Edition',
            'Smart Connected'
        ];

        foreach ($types as $index => $name) {
            DB::table('ms_product_types')->insert([
                'id'           => Str::uuid(),
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'is_activated' => 1,
            ]);
        }
    }
}
