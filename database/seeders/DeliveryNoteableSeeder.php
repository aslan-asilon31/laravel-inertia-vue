<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryNoteableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Ambil data pendukung
        $customers = DB::table('ms_customers')->select('id', 'name')->get();
        $employees = DB::table('ms_employees')->select('id', 'name')->get();
        $addresses = DB::table('ms_customer_addresses')->pluck('id');

        // Daftar source yang bisa menjadi delivery_noteable
        $sources = [
            ['table' => 'tr_service_receipt_header', 'class' => 'App\Models\TrServiceReceiptHeader'],
            ['table' => 'tr_service_return_header', 'class' => 'App\Models\TrServiceReturnHeader'],
            ['table' => 'tr_sales_order_header', 'class' => 'App\Models\TrSalesOrderHeader'],
        ];

        if ($customers->isEmpty() || $employees->isEmpty() || $addresses->isEmpty()) {
            $this->command->warn('Data Master kosong, seeder dibatalkan.');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            // Pilih source secara acak
            $source = $sources[array_rand($sources)];
            $target = DB::table($source['table'])->inRandomOrder()->first();

            if (!$target) continue;

            $deliveryNoteHeaderId = Str::uuid()->toString();
            $randomEmployee = $employees->random();
            $randomCustomer = $customers->random();

            // 2. Insert Delivery Note Header
            DB::table('tr_delivery_note_header')->insert([
                'id'                   => $deliveryNoteHeaderId,
                'id_delivery_noteable' => $target->id,
                'id_employee'       => $randomEmployee->id,
                'name_employee'     => $randomEmployee->name,
                'id_customer'       => $randomCustomer->id,
                'name_customer'     => $randomCustomer->name,
                'name'                 => 'DN-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status'               => 'terbit',
                'created_by'           => 'system',
                'updated_by'           => 'system',
                'created_at'           => $now,
                'updated_at'           => $now,
                'is_activated'         => 1,
            ]);

            // 3. Insert ke tabel pivot delivery_noteable
            DB::table('delivery_noteable')->insert([
                'id'                     => Str::uuid()->toString(),
                'type_delivery_noteable' => $source['class'],
                'id_delivery_noteable'   => $target->id,
                'ordinal'                => 1,
                'status'                 => 'terbit',
                'created_by'             => 'system',
                'updated_by'             => 'system',
                'created_at'             => $now,
                'updated_at'             => $now,
                'is_activated'           => 1,
            ]);
        }

        $this->command->info('Seeder DeliveryNoteable Successfully! ✅');
    }
}
