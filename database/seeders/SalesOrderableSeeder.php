<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesOrderableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Ambil data master
        $customers = DB::table('ms_customers')->select('id', 'name')->get();
        $employees = DB::table('ms_employees')->select('id', 'name')->get();
        $addresses = DB::table('ms_customer_addresses')->pluck('id');
        $serviceOrders = DB::table('tr_service_order_header')->get();

        if ($serviceOrders->isEmpty() || $customers->isEmpty()) {
            $this->command->warn('Data Service Order atau Customer kosong, seeder dilewati.');
            return;
        }

        // 2. Jalankan perulangan
        for ($i = 1; $i <= 3; $i++) {
            $currentServiceOrder = $serviceOrders->random();
            $salesOrderHeaderId = Str::uuid()->toString();
            $pivotId = Str::uuid()->toString(); // ID untuk tabel sales_orderable

            $randomEmployee = $employees->random();
            $randomCustomer = $customers->random();

            // A. INSERT KE TABEL PIVOT (sales_orderable)
            DB::table('sales_orderable')->insert([
                'id'                       => $pivotId,
                'type_sales_orderable'     => 'App\Models\TrServiceOrderHeader',
                'id_sales_orderable'       => $currentServiceOrder->id,
                'id_sales_order_header' => $salesOrderHeaderId,
                'ordinal'                  => 1,
                'status'                   => 'terbit',
                'created_at'               => $now,
                'is_activated'         => 1,
                'updated_at'               => $now,
            ]);

            // B. INSERT SALES ORDER HEADER
            DB::table('tr_sales_order_header')->insert([
                'id'                     => $salesOrderHeaderId,
                'id_sales_orderable'     => $pivotId, // Relasi ke ID pivot
                'id_employee'         => $randomEmployee->id,
                'name_employee'       => $randomEmployee->name,
                'id_customer'         => $randomCustomer->id,
                'name_customer'       => $randomCustomer->name,
                'id_customer_address' => $addresses->random(),
                'date'                   => $now,
                'number'                 => 'SO-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status'                 => 'terbit',
                'status_payment'         => 'settlement',
                'is_activated'         => 1,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            // C. FETCH PRODUK DARI SERVICE ORDER DETAIL
            $serviceDetails = DB::table('tr_service_order_detail')
                ->where('id_service_order_header', $currentServiceOrder->id)
                ->get();

            foreach ($serviceDetails as $index => $serviceDetail) {
                DB::table('tr_sales_order_detail')->insert([
                    'id'                        => Str::uuid()->toString(),
                    'id_sales_order_header'  => $salesOrderHeaderId,
                    'id_product'             => $serviceDetail->id_product,
                    'name_product'           => $serviceDetail->name_product,
                    'selling_price'             => rand(150000, 3500000),
                    'qty'                       => $serviceDetail->qty ?? 1,
                    'ordinal'                   => $index + 1,
                    'created_at'                => $now,
                    'updated_at'                => $now,
                ]);
            }
        }
    }
}
