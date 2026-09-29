<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccessRightGroupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();
            $actor = 'system';

            $standardActions = DB::table('ms_actions')->pluck('id')->toArray();

            // 2. Daftar kolom standar wajib di setiap tabel/komponen
            $standardColumns = [
                'id',
                'is_activated',
                'status',
                'selected',
                'action',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'ordinal',
                'remarks'
            ];

            // 3. KAMUS KOLOM KHUSUS PER TABEL (Custom Column Mapping)
            $specificColumnsMap = [];

            // 4. DAFTAR KOLOM YANG DIINGINKAN AGAR OTOMATIS TERCENTANG (DEFAULT ACTIVE)
            $defaultCheckedColumns = [
                'id',
                'status',
                'ordinal',
                'is_activated',
                'export-excel',
                'import-excel',
                'created_by',
                'updated_by',
                'updated_at',
                'created_at'
            ];

            // Daftar halaman khusus yang ingin di-set status 'draf'
            $draftPagesTarget = [
                'access-right-position',
                'access-right-position-status',
                'access-right-position-warehouse',
                'access-right-position-warehouse_branch'
            ];

            // 4. Ambil SEMUA halaman beserta icon-nya dari tabel ms_pages
            $pages = DB::table('ms_pages')->select('name', 'icon')->get();
            $halamanData = [];
            $pageIconMap = [];

            foreach ($pages as $page) {
                $pageName = $page->name;
                $slugPage = Str::snake($pageName);

                $pageIconMap[$slugPage] = $page->icon;

                if (in_array($slugPage, ['dashboard', 'stock_report', 'general_report', 'permission', 'hak_akses'])) {
                    $actions = ['list'];
                } else {
                    $actions = $standardActions;
                }

                foreach ($actions as $action) {
                    $halamanData[] = [
                        'name' => "{$slugPage}-{$action}",
                        'is_default_checked' => true
                    ];
                }

                if (!in_array($slugPage, ['dashboard', 'stock_report', 'general_report', 'permission', 'hak_akses'])) {
                    $columnsForThisPage = $standardColumns;

                    if (isset($specificColumnsMap[$slugPage])) {
                        $columnsForThisPage = array_unique(array_merge($standardColumns, $specificColumnsMap[$slugPage]));
                    }

                    foreach ($columnsForThisPage as $column) {
                        $isChecked = in_array($column, $defaultCheckedColumns);

                        $halamanData[] = [
                            'name' => "{$slugPage}-column_{$column}",
                            'is_default_checked' => $isChecked
                        ];
                    }
                }
            }

            // 5. Insert Access Right Groups 
            $existingGroups = DB::table('access_right_group')->pluck('name')->toArray();
            $groupOrdinal = DB::table('access_right_group')->max('ordinal') ?? 0;

            $insertGroups = [];
            $groupsProcessed = [];

            foreach ($halamanData as $data) {
                $fullName = $data['name'];
                $parts = explode('-', $fullName);
                $groupName = $parts[0];

                // Jika berakhiran _detail, arahkan group-nya ke induk tanpa _detail agar menjadi satu grup transaksi
                $pathGroupName = $groupName;
                if (str_ends_with($pathGroupName, '_detail')) {
                    $pathGroupName = str_replace('_detail', '', $pathGroupName);
                }

                $cleanGroupName = preg_replace('/^(tr|ms|rp)[_\-]/i', '', $pathGroupName);
                $dbGroupName = ucwords(str_replace('_', ' ', $cleanGroupName));

                if (!in_array($dbGroupName, $existingGroups) && !in_array($groupName, $groupsProcessed)) {
                    $groupOrdinal++;
                    $groupsProcessed[] = $groupName;
                    $existingGroups[] = $dbGroupName;

                    $category = 'master';
                    if (str_starts_with($groupName, 'tr_') || str_starts_with($groupName, 'tr-') || str_contains($groupName, 'transaksi')) {
                        $category = 'transaksi';
                    } elseif (in_array($groupName, ['dashboard', 'profile', 'profil', 'dashboard-welcome', 'dashboard-analytic'])) {
                        $category = 'umum';
                    } elseif (str_contains($groupName, 'report') || str_starts_with($groupName, 'rp_') || str_starts_with($groupName, 'rp-')) {
                        $category = 'report';
                    }

                    $groupIcon = $pageIconMap[$groupName] ?? 'o-cube';

                    $insertGroups[] = [
                        'id'            => (string) Str::uuid(),
                        'id_parent'     => null,
                        'name'          => $dbGroupName,
                        'path'          => '/' . str_replace('_', '-', $pathGroupName),
                        'status'        => 'terbit',
                        'category'      => $category,
                        'icon'          => $groupIcon,
                        'ordinal'       => $groupOrdinal,
                        'ordinal_child' => null,
                        'created_by'    => $actor,
                        'updated_by'    => $actor,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                        'is_activated'  => 1,
                    ];
                }
            }

            if (!empty($insertGroups)) {
                foreach (array_chunk($insertGroups, 100) as $chunk) {
                    DB::table('access_right_group')->insert($chunk);
                }
            }

            // 6. Bulk Insert Access Rights (Pastikan semua hak akses mendapatkan ID Group yang valid)
            $groupMap = DB::table('access_right_group')->pluck('id', 'name')->toArray();
            $rightOrdinal = DB::table('access_right')->max('ordinal') ?? 0;
            $insertAccessRights = [];

            foreach ($halamanData as $data) {
                $fullName = $data['name'];
                $parts = explode('-', $fullName);
                $groupName = $parts[0];
                $mapTargetGroupName = $groupName;

                if (str_ends_with($mapTargetGroupName, '_header')) {
                    $mapTargetGroupName = str_replace('_header', '', $mapTargetGroupName);
                }
                // Jika itu detail, petakan kembali ke grup utamanya (tanpa _detail)
                if (str_ends_with($mapTargetGroupName, '_detail')) {
                    $mapTargetGroupName = str_replace('_detail', '', $mapTargetGroupName);
                }

                $cleanGroupName = preg_replace('/^(tr|ms|rp)[_\-]/i', '', $mapTargetGroupName);
                $dbGroupName = ucwords(str_replace('_', ' ', $cleanGroupName));

                $groupId = $groupMap[$dbGroupName] ?? null;
                $rightOrdinal++;

                $isRightDraft = in_array($groupName, $draftPagesTarget);

                $insertAccessRights[] = [
                    'id'                    => (string) Str::uuid(),
                    'id_access_right_group' => $groupId, // 🌟 Sekarang terisi ID Group yang valid!
                    'name'                  => $data['name'],
                    'ordinal'               => $rightOrdinal,
                    'status'                => $isRightDraft ? 'draf' : 'terbit',
                    'created_by'            => $actor,
                    'updated_by'            => $actor,
                    'is_activated'          => $isRightDraft ? 0 : 1,
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ];
            }

            if (!empty($insertAccessRights)) {
                foreach (array_chunk($insertAccessRights, 100) as $chunk) {
                    DB::table('access_right')->insert($chunk);
                }
            }

            if ($this->command) {
                $this->command->info('✔ access_right dengan relasi id_access_right_group berhasil digenerate sepenuhnya!');
            }
        });
    }
}
