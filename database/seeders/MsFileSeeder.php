<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MsFileSeeder extends Seeder
{
    public function run(): void
    {
        $fileTypes = ['jpg', 'png', 'webp', 'pdf', 'mp4'];
        $targets = [
            'App\Models\TrServiceReceipt' => DB::table('tr_service_receipt_header')->pluck('id'),
            'App\Models\MsProduct'        => DB::table('ms_products')->pluck('id'),
            'App\Models\MsEmployee'       => DB::table('ms_employees')->pluck('id'),
        ];

        foreach ($targets as $type => $ids) {
            foreach ($ids as $id) {
                // Generate 1-2 file untuk setiap record
                for ($i = 0; $i < rand(1, 2); $i++) {
                    $ext = $fileTypes[array_rand($fileTypes)];

                    DB::table('ms_files')->insert([
                        'id'            => Str::uuid(),
                        'fileable_id'   => $id,
                        'fileable_type' => $type,
                        'url'           => 'files/' . Str::random(10) . '.' . $ext,
                        'type_file'     => $ext, // Simpan ekstensi asli
                        'status'        => 'terbit',
                        'created_by'    => 'system',
                        'created_at'    => now(),
                        'is_activated'  => 1,
                    ]);
                }
            }
        }

        $this->command->info('Seeder Master Employee Successfully! ✅');
    }
}
