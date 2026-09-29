<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MsBranch;

class AccessRightEmployeeBranchSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $allCabangIds = MsBranch::all()->pluck('id')->toArray();

        if (empty($allCabangIds)) {
            $this->command->warn('Data MsBranch kosong. Seeder cabang dilewati.');
            return;
        }

        $getRandomCabangId = fn() => $allCabangIds[array_rand($allCabangIds)];

        $data = [];
        $ordinal = 1;

        // Membuat sampel data relasi cabang
        for ($i = 0; $i < 6; $i++) {
            $data[] = [
                'id'         => (string) Str::uuid(),
                'cabang_id'  => $getRandomCabangId(),
                'ordinal'    => $ordinal++,
                'status'     => 'terbit',
                'is_activated' => 1,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_access_right_position_branch')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_access_right_position_branch')->insert($chunk);
        }

        $this->command->info('✔ AccessRightEmployeeBranchSeeder berhasil dijalankan!');
    }
}
