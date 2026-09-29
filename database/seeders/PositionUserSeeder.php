<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PositionUserSeeder extends Seeder
{
    public function run(): void
    {
        $jabatanId = DB::table('ms_positions')->value('id'); // Ambil 1 Position ID
        $userId = DB::table('users')->value('id');       // Ambil 1 user ID

        if ($jabatanId && $userId) {
            DB::table('pv_employee_position')->insert([
                'id' => Str::uuid(),
                'id_position' => $jabatanId,
                'user_id' => $userId,
                'created_by' => 'system',
                'updated_by' => 'system',

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $this->command->warn('Seeder gagal: Pastikan ada data di tabel Position dan users.');
        }
    }
}
