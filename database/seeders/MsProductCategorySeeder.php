<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class MsProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Home Appliances',
            'Air Purifier',
            'Vacuum Cleaner',
            'Kitchenware',
            'Personal Care',
            'Smart Home',
            'Cleaning Tools',
            'Accessories',
        ];

        $now = Carbon::now();
        $data = [];

        foreach ($categories as $index => $name) {
            $data[] = [
                'id'           => (string) Str::uuid(),
                'name'         => $name,
                'slug'         => Str::slug($name),
                'ordinal'      => $index + 1,
                'status'       => Arr::random(['terbit', 'draf', 'batal']),
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ];
        }

        DB::table('ms_product_categories')->insert($data);
        $this->command->info('Seeder Master Product Category Successfully! ✅');
    }
}
