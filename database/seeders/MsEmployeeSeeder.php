<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MsEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil semua posisi yang sudah ada sekaligus untuk menghindari query berulang
        $positions = DB::table('ms_positions')->whereIn('name', ['super', 'admin', 'staff', 'supplier'])->pluck('id', 'name');

        $requiredPositions = ['super' => 1, 'admin' => 2, 'staff' => 3, 'supplier' => 4];
        $positionIds = [];

        foreach ($requiredPositions as $posName => $ordinal) {
            if (!isset($positions[$posName])) {
                $newId = (string) Str::uuid();
                DB::table('ms_positions')->insert([
                    'id'           => $newId,
                    'name'         => $posName,
                    'ordinal'      => $ordinal,
                    'created_by'   => 'system',
                    'updated_by'   => 'system',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'is_activated' => true,
                ]);
                $positionIds[$posName] = $newId;
            } else {
                $positionIds[$posName] = $positions[$posName];
            }
        }

        $superPositionId = $positionIds['super'];
        $adminPositionId      = $positionIds['admin'];
        $staffPositionId      = $positionIds['staff'];
        $supplierPositionId   = $positionIds['supplier'];

        // 2. Data List Karyawan & Supplier
        $employees = [
            // --- SUPER ---
            ['name' => 'super', 'username' => 'super', 'role' => 'super', 'id_position' => $superPositionId, 'email' => 'super@company.com'],
            ['name' => 'Aslan Asilon', 'username' => 'aslan', 'role' => 'super', 'id_position' => $superPositionId, 'email' => 'aslan.asilon@company.com'],

            // --- ADMIN ---
            ['name' => 'Admin System', 'username' => 'admin', 'role' => 'admin', 'id_position' => $adminPositionId, 'email' => 'admin@company.com'],

            // --- STAFF ---
            ['name' => 'Rizky Hidayat', 'username' => 'rizky', 'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'rizky.hidayat@company.com'],

            // --- SUPPLIER ---
            ['name' => 'Shengda Teknologi (盛大科技)', 'username' => 'shengda_tek', 'role' => 'supplier', 'id_position' => $supplierPositionId, 'email' => 'info@shengda.com'],
            ['name' => 'Mingxing Trading (明星贸易)', 'username' => 'mingxing_trad', 'role' => 'supplier', 'id_position' => $supplierPositionId, 'email' => 'contact@mingxing.com'],
            ['name' => 'Fuqiang Electronics (富强电子)', 'username' => 'fuqiang_elek', 'role' => 'supplier', 'id_position' => $supplierPositionId, 'email' => 'sales@fuqiang.com'],
            ['name' => 'Tianhe Industry (天和实业)', 'username' => 'tianhe_ind', 'role' => 'supplier', 'id_position' => $supplierPositionId, 'email' => 'support@tianhe.com'],
            ['name' => 'Fengling Hardware (丰岭五金)', 'username' => 'fengling_hard', 'role' => 'supplier', 'id_position' => $supplierPositionId, 'email' => 'info@fengling.com'],

            // --- STAFF LAINNYA ---
            ['name' => 'Tanaka Hiroshi',   'username' => 'tanaka',    'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'tanaka.hiroshi@company.com'],
            ['name' => 'Sato Yuki',        'username' => 'sato',      'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'sato.yuki@company.com'],
            ['name' => 'Suzuki Kenji',     'username' => 'suzuki',    'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'suzuki.kenji@company.com'],
            ['name' => 'Takahashi Aoi',    'username' => 'takahashi', 'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'takahashi.aoi@company.com'],
            ['name' => 'Watanabe Rina',    'username' => 'watanabe',  'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'watanabe.rina@company.com'],
            ['name' => 'Ito Haruto',       'username' => 'ito',       'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'ito.haruto@company.com'],
            ['name' => 'Nakamura Sakura',  'username' => 'nakamura',  'role' => 'staff', 'id_position' => $staffPositionId, 'email' => 'nakamura.sakura@company.com'],
        ];

        $existingEmployees = DB::table('ms_employees')->pluck('id', 'name');

        $employeeInserts = [];
        $pivotInserts = [];
        $accountInserts = [];
        $detailInserts = [];
        $sessionInserts = [];

        foreach ($employees as $index => $data) {
            $employeeId = $existingEmployees[$data['name']] ?? (string) Str::uuid();
            $currentOrdinal = $index + 1;

            $employeeInserts[] = [
                'id'           => $employeeId,
                'name'         => $data['name'],
                'phone'        => '08' . rand(111111111, 999999999),
                'email'        => $data['email'],
                'ordinal'      => $currentOrdinal,
                'status'       => 'terbit',
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => true,
            ];

            // Tabel pivot: pv_employee_position
            $pivotInserts[] = [
                'id'            => (string) Str::uuid(),
                'id_employee'   => $employeeId,
                'id_position'   => $data['id_position'],
                'ordinal'       => $currentOrdinal,
                'status'        => 'terbit',
                'is_activated'  => true,
                'created_by'    => 'system',
                'updated_by'    => 'system',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];

            // Tabel ms_employee_accounts (Menggantikan sebagian data akun lama)
            $accountInserts[] = [
                'id_employee'          => $employeeId,
                'name'                 => $data['name'],
                'username'             => $data['username'],
                'password'             => Hash::make('123456'),
                'tgl_verifikasi_email' => $now,
                'remember_token'       => Str::random(10),
                'status'               => 'terbit',
                'created_by'           => 'system',
                'updated_by'           => 'system',
                'created_at'           => $now,
                'updated_at'           => $now,
            ];

            // Tabel ms_employee_details (Sesuai skema baru dengan ID UUID primer)
            $detailInserts[] = [
                'id'                  => (string) Str::uuid(),
                'id_employee'         => $employeeId,
                'username'            => $data['username'],
                'date_birth'          => '1998-01-01',
                'gender'              => 'Laki-laki',
                'website'             => null,
                'desc'                => null,
                'status'              => 'terbit',
                'ordinal'             => $currentOrdinal,
                'account_verified_at' => $now,
                'password'            => Hash::make('123456'),
                'remember_token'      => Str::random(10),
                'created_by'          => 'system',
                'updated_by'          => 'system',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_activated'        => true,
            ];

            $sessionInserts[] = [
                'id'            => (string) Str::uuid(),
                'id_employee'   => $employeeId,
                'is_logged'     => true,
                'sesid'         => (string) Str::uuid(),
                'code_employee' => 'EMP-' . strtoupper(Str::random(5)),
                'name_employee' => $data['username'],
                'role'          => $data['role'],
                'logged_in_at'  => $now,
                'logged_out_at' => null,
            ];
        }

        if (!empty($employeeInserts)) {
            DB::table('ms_employees')->upsert($employeeInserts, ['name'], ['phone', 'email', 'ordinal', 'status', 'updated_by', 'updated_at', 'is_activated']);
        }

        if (!empty($pivotInserts)) {
            foreach (array_chunk($pivotInserts, 500) as $chunk) {
                DB::table('pv_employee_position')->upsert($chunk, ['id_employee', 'id_position'], ['ordinal', 'status', 'is_activated', 'updated_by', 'updated_at']);
            }
        }

        if (!empty($accountInserts)) {
            foreach (array_chunk($accountInserts, 500) as $chunk) {
                DB::table('ms_employee_accounts')->upsert($chunk, ['username'], ['name', 'password', 'tgl_verifikasi_email', 'remember_token', 'status', 'updated_by', 'updated_at']);
            }
        }

        if (!empty($detailInserts)) {
            foreach (array_chunk($detailInserts, 500) as $chunk) {
                DB::table('ms_employee_details')->upsert($chunk, ['id_employee'], ['username', 'date_birth', 'gender', 'website', 'desc', 'ordinal', 'status', 'account_verified_at', 'password', 'remember_token', 'updated_by', 'updated_at', 'is_activated']);
            }
        }

        if (!empty($sessionInserts)) {
            foreach (array_chunk($sessionInserts, 500) as $chunk) {
                DB::table('ms_employee_sessions')->upsert($chunk, ['id_employee'], ['is_logged', 'sesid', 'code_employee', 'name_employee', 'role', 'logged_in_at', 'logged_out_at']);
            }
        }

        $this->command->info('✔ Seeder Master Employee & super berhasil dijalankan dengan super cepat! 🚀');
    }
}
