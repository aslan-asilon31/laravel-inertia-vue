<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\MsStatus;
use App\Models\AccessRightPosition;

class AccessRightPositionStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $statuses = MsStatus::all();
        $accessRightPositions = AccessRightPosition::all();

        if ($statuses->isEmpty()) {
            $this->command->warn('Tabel ms_status masih kosong! Seeder dilewati.');
            return;
        }

        if ($accessRightPositions->isEmpty()) {
            $this->command->warn('Tabel access_right_position masih kosong! Seeder dilewati.');
            return;
        }

        // Ambil ID status 'publish' / 'terbit' jika ada, atau gunakan status pertama sebagai default
        $defaultStatusId = $statuses->whereIn('name', ['publish', 'terbit'])->first()->id ?? $statuses->first()->id;

        $data = [];
        $ordinal = 1;

        // Hubungkan setiap access_right_position dengan status aktif default
        foreach ($accessRightPositions as $arp) {
            $data[] = [
                'id'                       => (string) Str::uuid(),
                // 'ms_status_id'             => $defaultStatusId,
                'id_access_right_position' => $arp->id,
                'is_activated'             => 1,
                'status' => 'terbit',
                'ordinal'                  => $ordinal++,
                'created_by'               => 'system',
                'updated_by'               => 'system',
                'created_at'               => $now,
                'updated_at'               => $now,
            ];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_access_right_position_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_access_right_position_status')->insert($chunk);
        }

        $this->command->info('✔ AccessRightPositionStatusSeeder berhasil di-seed secara terstruktur!');
    }
}
