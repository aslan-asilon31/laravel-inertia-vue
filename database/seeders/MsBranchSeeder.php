<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MsBranchSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $branches = [
            'Jakarta',
            'Surabaya',
            'Bandung',
            'Semarang',
            'Medan',
        ];

        $data = [];
        $ordinal = 1;

        foreach ($branches as $branchName) {
            $data[] = [
                'id'           => (string) Str::uuid(),
                'name'         => $branchName,
                'status'       => 'terbit',
                'ordinal'      => $ordinal++,
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ];
        }

        // Nonaktifkan foreign key check sementara jika ada tabel relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ms_branches')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('ms_branches')->insert($data);

        $this->command->info('✔ MsBranchSeeder berhasil dijalankan!');
    }
}
