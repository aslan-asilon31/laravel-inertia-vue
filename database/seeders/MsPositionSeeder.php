<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MsPositionSeeder extends Seeder
{
    public function run()
    {
        // List posisi retail menggunakan nama lowercase untuk pencarian yang konsisten pada kode program
        $positions = [
            'store manager',
            'sales associate',
            'supplier',
            'cashier',
            'warehouse staff',
            'delivery staff',
            'admin',
            'customer service',
            'technician',
            'supervisor',
            'marketing',
        ];

        foreach ($positions as $index => $name) {
            // Gunakan updateOrInsert berdasarkan nama agar tidak duplikat jika seeder dijalankan ulang
            DB::table('ms_positions')->updateOrInsert(
                ['name' => $name], // Kunci unik pengecekan
                [
                    // Jika data belum ada, generate UUID baru. Jika sudah ada, id lama tetap dipertahankan.
                    'id'           => DB::table('ms_positions')->where('name', $name)->value('id') ?? (string) Str::uuid(),
                    'ordinal'      => $index + 1,
                    'status'   => 'terbit',
                    'created_by'   => 'system',
                    'updated_by'   => 'system',
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now(),
                    'is_activated' => true,
                ]
            );
        }
        $this->command->info('Seeder Master Position Successfully! ✅');
    }
}
