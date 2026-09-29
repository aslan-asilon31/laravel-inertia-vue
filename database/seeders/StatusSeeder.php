<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Draf', 'ordinal' => 1, 'is_activated' => 1],
            ['name' => 'Terbit', 'ordinal' => 2, 'is_activated' => 1],
            ['name' => 'Proses', 'ordinal' => 3, 'is_activated' => 1],
            ['name' => 'Selesai', 'ordinal' => 4, 'is_activated' => 1],
            ['name' => 'Batal', 'ordinal' => 5, 'is_activated' => 1],
            ['name' => 'Tidak Aktif', 'ordinal' => 6, 'is_activated' => 0],
        ];

        $data = [];

        foreach ($statuses as $status) {
            $data[] = [
                'id'           => (string) Str::uuid(),
                'name'         => $status['name'],
                'slug'         => Str::slug($status['name']),
                'ordinal'      => $status['ordinal'],
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'is_activated' => $status['is_activated'],
            ];
        }

        DB::table('ms_statuses')->insert($data);
    }
}
