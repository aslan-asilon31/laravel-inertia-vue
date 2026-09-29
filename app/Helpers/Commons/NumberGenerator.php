<?php

namespace App\Helpers\Commons;

use Illuminate\Support\Facades\DB;

class NumberGenerator
{
    /**
     * @param string $lastNumber Contoh: "BPManual/DC5-001/2026-0001"
     * @param string $warehouseName Contoh: "DC5"
     */
    public static function generateOrderNumber(?string $lastNumber, string $warehouseName, string $prefix = 'SR'): string
    {
        $lastNumber = $lastNumber ?? '';
        $warehouseName = $warehouseName ?? '';
        $year = date('Y');
        $month = date('m'); // Mengambil bulan saat ini (01-12)

        $warehouseIndex = 1;
        $monthIndex = 1;

        // Regex disesuaikan: SR / DC5 - 001 / 2026 - 05 - 0001
        // Memecah menjadi: [1]Warehouse, [2]WarehouseIndex, [3]Year, [4]Month, [5]MonthIndex
        if (preg_match('/\/([A-Za-z0-9]+)-(\d+)\/(\d+)-(\d+)-(\d+)/', $lastNumber, $matches)) {
            $lastWarehouseName = $matches[1];
            $lastYear = (int) $matches[3];
            $lastMonth = $matches[4];
            $lastMonthIndex = (int) $matches[5];

            // LOGIKA: Jika warehouse sama, tahun sama, DAN bulan sama
            if ($lastWarehouseName === $warehouseName && $lastYear == $year && $lastMonth === $month) {
                $warehouseIndex = (int) $matches[2]; // Pertahankan index warehouse
                $monthIndex = $lastMonthIndex + 1;   // Increment index bulanan
            }
            // Jika beda warehouse, tahun, atau bulan
            else {
                $warehouseIndex = ($lastWarehouseName === $warehouseName) ? (int)$matches[2] : 1;
                // monthIndex tetap 1 (reset karena bulan/tahun baru atau warehouse baru)
            }
        }

        return sprintf(
            '%s/%s-%03d/%s-%02d-%04d',
            $prefix,
            $warehouseName,
            $warehouseIndex,
            $year,
            $month,
            $monthIndex
        );
    }
}
