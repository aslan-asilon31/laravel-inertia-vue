<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeePositionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil seluruh data karyawan dan posisi yang tersedia
        $employees = DB::table('ms_employees')->get();
        $positions = DB::table('ms_positions')->get();

        if ($employees->isEmpty()) {
            $this->command->warn('Tabel ms_employees masih kosong! Jalankan MsEmployeeSeeder terlebih dahulu.');
            return;
        }

        if ($positions->isEmpty()) {
            $this->command->warn('Tabel ms_positions masih kosong! Jalankan seeder posisi terlebih dahulu.');
            return;
        }

        // 2. Petakan posisi berdasarkan nama (lowercase) untuk pencocokan yang akurat
        $positionMap = $positions->mapWithKeys(fn($pos) => [strtolower($pos->name) => $pos->id])->toArray();

        // Tentukan mapping role/username khusus jika diperlukan
        $superPositionId = $positionMap['super'] ?? reset($positionMap);
        $adminPositionId      = $positionMap['admin'] ?? $superPositionId;
        $staffPositionId      = $positionMap['staff'] ?? $superPositionId;
        $supplierPositionId   = $positionMap['supplier'] ?? $superPositionId;

        $data = [];
        $ordinal = 1;

        // 3. Hubungkan setiap karyawan dengan posisinya yang sesuai
        foreach ($employees as $employee) {
            // Cek detail employee untuk menentukan posisinya berdasarkan username/nama
            $detail = DB::table('ms_employee_details')->where('id_employee', $employee->id)->first();
            $username = $detail->username ?? strtolower($employee->name);

            // Tentukan posisi berdasarkan kategori username/nama
            if (in_array($username, ['super', 'aslan'])) {
                $targetPositionId = $superPositionId;
            } elseif ($username === 'admin') {
                $targetPositionId = $adminPositionId;
            } elseif (str_contains($username, '_tek') || str_contains($username, '_trad') || str_contains($username, '_elek') || str_contains($username, '_ind') || str_contains($username, '_hard') || in_array($username, ['shengda_tek', 'mingxing_trad', 'fuqiang_elek', 'tianhe_ind', 'fengling_hard'])) {
                $targetPositionId = $supplierPositionId;
            } else {
                $targetPositionId = $staffPositionId;
            }

            $data[] = [
                'id'             => (string) Str::uuid(),
                'id_position' => $targetPositionId,
                'id_employee' => $employee->id,
                'ordinal'        => $ordinal++,
                'status'         => 'terbit',
                'is_activated'   => 1,
                'created_by'     => 'system',
                'updated_by'     => 'system',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // 4. Nonaktifkan foreign key check sementara untuk keamanan truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pv_employee_position')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 5. Masukkan data ke database menggunakan chunk
        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('pv_employee_position')->insert($chunk);
        }

        $this->command->info('✔ EmployeePositionSeeder berhasil dijalankan dengan pemetaan role yang akurat!');
    }
}
