<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MsCustomerAddressSeeder extends Seeder
{
    public function run()
    {
        $customers = DB::table('ms_customers')->pluck('id');

        $addresses = [
            'Jl. Jenderal Sudirman No. 52, RT 05/RW 03, Karet Tengsin, Tanah Abang, Jakarta Pusat 10220',
            'Jl. MH Thamrin No. 8, RT 01/RW 02, Kebon Sirih, Menteng, Jakarta Pusat 10340',
            'Jl. Gatot Subroto Kav. 27, RT 02/RW 04, Kuningan Timur, Setiabudi, Jakarta Selatan 12950',
            'Jl. HR Rasuna Said Blok X5, RT 03/RW 01, Karet Kuningan, Setiabudi, Jakarta Selatan 12940',
            'Jl. Panglima Polim Raya No. 15, RT 06/RW 07, Melawai, Kebayoran Baru, Jakarta Selatan 12160',
            'Jl. Kemang Raya No. 10, RT 04/RW 02, Bangka, Mampang Prapatan, Jakarta Selatan 12730',
            'Jl. Pluit Karang Ayu Barat No. 12, RT 01/RW 08, Pluit, Penjaringan, Jakarta Utara 14450',
            'Jl. Boulevard Barat Raya Blok LC6, RT 05/RW 12, Kelapa Gading Barat, Jakarta Utara 14240',
            'Jl. Daan Mogot KM 11 No. 23, RT 07/RW 03, Kalideres, Jakarta Barat 11840',
            'Jl. Panjang No. 45, RT 02/RW 10, Kebon Jeruk, Jakarta Barat 11530',
            'Jl. Pemuda No. 70, RT 08/RW 05, Rawamangun, Pulogadung, Jakarta Timur 13220',
            'Jl. Raya Bogor KM 18 No. 5, RT 03/RW 04, Kramat Jati, Jakarta Timur 13510',
        ];

        foreach ($customers as $i => $customerId) {
            DB::table('ms_customer_addresses')->insert([
                'id' => Str::uuid(),
                'id_customer' => $customerId,
                'address' => $addresses[$i % count($addresses)],
                'status' => 'terbit',
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_activated' => 1,
            ]);
        }

        $this->command->info('Seeder Master Customer Address Successfully! ✅');
    }
}
