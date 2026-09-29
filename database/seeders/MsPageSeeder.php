<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'Dashboard',
            'Dashboard Analytic',
            'Profile',

            // master page
            'Ms Action',
            'Ms Employee',
            'Ms Customer',
            'Ms Position',
            'Ms Category Pengajuan',
            'Ms Ground Rules',
            'Ms Channel',
            'Ms Product',
            'Ms Product Category',
            'Ms Product Type',
            'Ms Product Gift',

            // transaction page
            'Tr Pengajuan Gift',
            'Tr Pengajuan Gift Detail',
            'Tr stock movement',

            // report page
            'Stock Report',
            'General Report',

            // authentication and authorization page
            'Access Right',
            'Access Right Group',

        ];

        $now = now();

        // Daftar halaman yang ingin diset draf dan tidak aktif secara khusus
        $draftPages = [
            'Access Right Position',
            'Access Right Position Status',
            'Access Right Position Warehouse',
            'Access Right Position Warehouse Branch',
            'Tr Pengajuan Gift Detail'
        ];

        foreach ($pages as $index => $name) {
            $lowerName = strtolower($name);
            $icon = 'o-cube'; // Default icon

            // 1. Dashboard & Profile
            if (str_contains($lowerName, 'dashboard')) {
                $icon = 'o-home';
            } elseif (str_contains($lowerName, 'profile') || str_contains($lowerName, 'employee')) {
                $icon = 'o-user';

                // 2. Partners (Customer / Supplier)
            } elseif (str_contains($lowerName, 'customer') || str_contains($lowerName, 'supplier')) {
                $icon = 'o-users';

                // 3. Products & Attributes
            } elseif (str_contains($lowerName, 'product') || str_contains($lowerName, 'brand') || str_contains($lowerName, 'measurement')) {
                $icon = 'o-tag';

                // 4. Warehouse & Location
            } elseif (str_contains($lowerName, 'warehouse') || str_contains($lowerName, 'rack')) {
                $icon = 'o-building-storefront';

                // 5. TRANSACTIONS (Variasi Icon Spesifik)
            } elseif (str_contains($lowerName, 'service')) {
                $icon = 'o-wrench-screwdriver';
            } elseif (str_contains($lowerName, 'invoice')) {
                $icon = 'o-document-currency-dollar';
            } elseif (str_contains($lowerName, 'payment')) {
                $icon = 'o-credit-card';
            } elseif (str_contains($lowerName, 'purchase request')) {
                $icon = 'o-document-plus';
            } elseif (str_contains($lowerName, 'purchase order') || str_contains($lowerName, 'purchase price')) {
                $icon = 'o-shopping-cart';
            } elseif (str_contains($lowerName, 'sales order')) {
                $icon = 'o-shopping-bag';
            } elseif (str_contains($lowerName, 'good receipt')) {
                $icon = 'o-inbox-arrow-down';
            } elseif (str_contains($lowerName, 'return')) {
                $icon = 'o-arrow-uturn-left';
            } elseif (str_contains($lowerName, 'picking')) {
                $icon = 'o-clipboard-document-list';
            } elseif (str_contains($lowerName, 'putaway')) {
                $icon = 'o-arrow-down-on-square';
            } elseif (str_contains($lowerName, 'delivery') || str_contains($lowerName, 'shipping')) {
                $icon = 'o-truck';
            } elseif (str_contains($lowerName, 'detail')) {
                $icon = 'o-list-bullet'; // Icon khusus untuk halaman detail transaksi

                // 6. Reports
            } elseif (str_contains($lowerName, 'report')) {
                $icon = 'o-chart-bar';

                // 7. Authorization & System Settings
            } elseif (str_contains($lowerName, 'permission') || str_contains($lowerName, 'access') || str_contains($lowerName, 'page') || str_contains($lowerName, 'position') || str_contains($lowerName, 'status') || str_contains($lowerName, 'action')) {
                $icon = 'o-shield-check';
            }

            // Tentukan status dan is_activated berdasarkan apakah nama halaman ada di dalam array $draftPages
            $status = in_array($name, $draftPages) ? 'draf' : 'terbit';
            $isActivated = in_array($name, $draftPages) ? false : true;

            DB::table('ms_pages')->insert([
                'id'           => Str::uuid()->toString(),
                'name'         => $name,
                'status'       => $status,       // Berubah jadi 'draf' untuk 4 halaman tersebut
                'icon'         => $icon,
                'ordinal'      => $index + 1,
                'created_by'   => 'System',
                'updated_by'   => 'System',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => $isActivated,  // Berubah jadi false untuk 4 halaman tersebut
            ]);
        }

        $this->command->info('Seeder Master Page Successfully with Diverse Icons! ✅');
    }
}
