<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HakAksesPegawaiCabangSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $allCabangIds = \App\Models\MsBranch::all()->pluck('id')->toArray();

        $getRandomCabangId = function () use ($allCabangIds) {
            return $allCabangIds[array_rand($allCabangIds)];
        };

        DB::table('pv_access_right_position_branch')->insert([
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid(),
                'cabang_id' => $getRandomCabangId(),
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ]);
    }
}
