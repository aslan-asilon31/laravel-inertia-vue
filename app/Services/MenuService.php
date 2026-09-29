<?php

namespace App\Services;

use App\Models\MsEmployeeAccount;
use Illuminate\Support\Facades\DB;

class MenuService
{
    /**
     * Mengambil dan menyusun struktur menu secara dinamis dari tabel access_right_group.
     */
    public static function getStructure(?MsEmployeeAccount $account): array
    {
        if (!$account) {
            return [];
        }

        // Ambil data menu dari tabel access_right_group yang statusnya terbit dan aktif
        // Urutkan berdasarkan kolom ordinal
        $groups = DB::table('access_right_group')
            ->where('status', 'terbit')
            ->where('is_activated', 1)
            ->orderBy('ordinal', 'asc')
            ->get();

        // Kelompokkan berdasarkan kategori (master, transaksi, report, umum, dll)
        $structuredMenu = [];
        
        // Definisikan label kategori agar terlihat rapi di sidebar
        $categoryLabels = [
            'umum' => 'Umum',
            'master' => 'Master Data',
            'transaksi' => 'Transaksi',
            'report' => 'Laporan',
        ];

        // Buat pengelompokan secara otomatis
        foreach ($groups as $group) {
            $category = $group->category ?? 'master';
            
            // Tentukan judul kategori jika belum ada di array penampung
            if (!isset($structuredMenu[$category])) {
                $structuredMenu[$category] = [
                    'title' => $categoryLabels[$category] ?? ucwords($category),
                    'items' => []
                ];
            }

            // Masukkan menu ke dalam kategori yang bersangkutan
            $structuredMenu[$category]['items'][] = [
                'name' => $group->name,
                'url' => $group->path,
                'icon' => $group->icon ?? 'o-cube',
            ];
        }

        // Kembalikan dalam bentuk array terindeks
        return array_values($structuredMenu);
    }
}