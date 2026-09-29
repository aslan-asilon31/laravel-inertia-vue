<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GiftDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $systemUser = 'system';

        // 1. SEEDER MS_PRODUCT_GIFT
        $gifts = [
            ['name' => 'Gift Box Eksklusif Umedalife', 'stock' => 150],
            ['name' => 'Tumbler Stainless Steel Premium', 'stock' => 85],
            ['name' => 'Pouch Kulit Multifungsi', 'stock' => 200],
            ['name' => 'Kalender Meja Custom 2026', 'stock' => 500],
        ];

        $giftIds = [];
        foreach ($gifts as $index => $item) {
            $id = (string) Str::uuid();
            $giftIds[] = $id;

            DB::table('ms_product_gift')->insert([
                'id'           => $id,
                'name'         => $item['name'],
                'stock'        => $item['stock'],
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => $systemUser,
                'updated_by'   => $systemUser,
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        // 2. SEEDER MS_CATEGORY_PENGAJUAN
        $categories = ['Customer VIP', 'KOL / Influencer', 'Campaign Internal', 'Event Khusus'];
        foreach ($categories as $index => $name) {
            DB::table('ms_category_pengajuan')->insert([
                'id'           => (string) Str::uuid(),
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => $systemUser,
                'updated_by'   => $systemUser,
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        // 3. SEEDER MS_GROUND_RULES
        $groundRules = ['Maksimal 1 gift per customer', 'Wajib melampirkan bukti resi/transaksi', 'KOL minimal 10k followers'];
        foreach ($groundRules as $index => $name) {
            DB::table('ms_ground_rules')->insert([
                'id'           => (string) Str::uuid(),
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => $systemUser,
                'updated_by'   => $systemUser,
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        // 4. SEEDER MS_CHANNELS
        $channels = ['Service Center', 'Instagram', 'Facebook', 'TikTok'];
        $channelIds = [];
        foreach ($channels as $index => $name) {
            $id = (string) Str::uuid();
            $channelIds[] = $id;
            DB::table('ms_channels')->insert([
                'id'           => $id,
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => $systemUser,
                'updated_by'   => $systemUser,
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        // 5. SEEDER MS_SPECIAL_CASES
        $specialCases = ['Birthday Gift', 'Campaign Promo Akhir Tahun', 'VIP Customer Request'];
        foreach ($specialCases as $index => $name) {
            DB::table('ms_special_cases')->insert([
                'id'           => (string) Str::uuid(),
                'name'         => $name,
                'status'       => 'terbit',
                'ordinal'      => $index + 1,
                'created_by'   => $systemUser,
                'updated_by'   => $systemUser,
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        // 6. SEEDER TR_PENGAJUAN_GIFT (HEADER) & DETAIL
        $headerId = (string) Str::uuid();
        DB::table('tr_pengajuan_gift')->insert([
            'id'            => $headerId,
            'id_employee'   => (string) Str::uuid(),
            'name_employee' => 'Admin Umedalife',
            'id_channel'    => $channelIds[0] ?? null,
            'name_channel'  => 'Service Center',
            'id_customer'   => (string) Str::uuid(),
            'name_customer' => 'Budi Santoso',
            'name'          => 'Pengajuan Gift Customer VIP',
            'remarks'       => 'Permintaan gift untuk loyal customer.',
            'ordinal'       => 1,
            'status'        => 'terbit',
            'created_by'    => $systemUser,
            'updated_by'    => $systemUser,
            'created_at'    => $now,
            'updated_at'    => $now,
            'is_activated'  => 1,
        ]);

        // Detail Transaksi Pengajuan Gift
        if (!empty($giftIds)) {
            DB::table('tr_pengajuan_gift_detail')->insert([
                'id'                        => (string) Str::uuid(),
                'id_pengajuan_gift_header'  => $headerId,
                'id_product'                => $giftIds[0], // Mengarah ke id dari ms_product_gift
                'name_product'              => 'Gift Box Eksklusif Umedalife',
                'stock'                     => 2,
                'remarks'                   => 'Kirimkan ke alamat customer.',
                'status_request'            => 'waiting',
                'status_priority'           => 'normal',
                'status'                    => 'terbit',
                'ordinal'                   => 1,
                'created_by'                => $systemUser,
                'updated_by'                => $systemUser,
                'created_at'                => $now,
                'updated_at'                => $now,
                'is_activated'              => 1,
            ]);
        }
    }
}
