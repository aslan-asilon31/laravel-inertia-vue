<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MsPosition;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PositionPermission;

class PositionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1. Ambil semua data posisi dan permission
        $positions = MsPosition::all();
        $permissions = Permission::all();

        if ($positions->isEmpty() || $permissions->isEmpty()) {
            $this->command->warn('Data posisi atau permission masih kosong.');
            return;
        }

        // 2. Ambil data yang sudah ada di DB untuk mencegah duplicate entry
        $existingKeys = PositionPermission::select('id_position', 'id_permission')
            ->get()
            ->map(fn($item) => "{$item->id_position}-{$item->permission_id}")
            ->flip()
            ->toArray();

        $insertData = [];
        $now = now();

        // 3. Ambil nilai max ordinal SEKALI SAJA di awal untuk menghindari query berulang di dalam loop
        $currentOrdinal = DB::table('position_permission')->max('ordinal') ?? 0;

        // 4. Kumpulkan data ke dalam Array tanpa query ke DB di dalam loop
        foreach ($positions as $position) {
            foreach ($permissions as $permission) {
                $comboKey = "{$position->id}-{$permission->id}";

                // Cek apakah kombinasi ini sudah ada di DB sebelumnya
                if (isset($existingKeys[$comboKey])) {
                    continue;
                }

                $currentOrdinal++;

                $insertData[] = [
                    'id'             => (string) Str::uuid(),
                    'id_position' => $position->id,
                    'id_permission'  => $permission->id,
                    'ordinal'        => $currentOrdinal,
                    'is_activated'   => 1,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];

                // Catat juga ke existingKeys agar tidak terjadi duplikasi jika ada data kembar di memori
                $existingKeys[$comboKey] = true;
            }
        }

        // 5. Lakukan Bulk Insert menggunakan Chunking per 1000 baris agar aman dari memory limit
        if (!empty($insertData)) {
            $chunks = array_chunk($insertData, 1000);
            foreach ($chunks as $chunk) {
                DB::table('position_permission')->insert($chunk);
            }
        }

        $this->command->info('Bulk Insert PositionPermission berhasil dilakukan dengan super cepat! 🚀');
    }
}
