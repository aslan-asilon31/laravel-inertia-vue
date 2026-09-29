<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MsCustomerSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Aslan Asilon',
            'Andi Pratama',
            'Budi Santoso',
            'Citra Lestari',
            'Dewi Anggraini',
            'Eko Saputra',
            'Fajar Nugroho',
            'Gita Permata',
            'Hendra Wijaya',
            'Intan Sari',
            'Joko Susilo'
        ];

        foreach ($names as $i => $name) {
            DB::table('ms_customers')->insert([
                'id' => Str::uuid(),
                'name' => $name,
                'phone' => '08' . rand(1111111111, 9999999999),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'ordinal' => $i + 1,
                'status'       => \Illuminate\Support\Arr::random(['terbit', 'draf', 'batal']),
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_activated' => 1,
            ]);
        }

        $this->command->info('Seeder Master Customer Successfully! ✅');
    }
}
