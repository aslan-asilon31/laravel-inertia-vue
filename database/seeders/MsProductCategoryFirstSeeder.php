<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MsproductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            'Air Purifier' => [
                ['nama' => 'Air360', 'deskripsi' => 'Hepa 13 Air Purifier'],
                // ['nama' => 'Air360 Studio', 'deskripsi' => 'Hepa 13 Air Purifier'],
                // ['nama' => 'AQ400', 'deskripsi' => 'Smart Air Purifier Hepa 13'],
                // ['nama' => 'AQ750', 'deskripsi' => 'Smart Air Purifier Hepa 13'],
                // ['nama' => 'Dehumidifier UDH1500', 'deskripsi' => 'Smart Dehumidifier'],
            ],
            // 'Audio' => [
            //     ['nama' => 'Besu', 'deskripsi' => 'Bluetooth Speaker Table'],
            // ],
            // 'Beauty & Care' => [
            //     ['nama' => 'UTH 700', 'deskripsi' => 'Travel Hair Dryer'],
            //     ['nama' => 'ION800', 'deskripsi' => 'Ionic Hair Dryer'],
            // ],
            // 'Kitchen' => [
            // ['nama' => 'Bru', 'deskripsi' => 'Multi Capsule Espresso Machine'],
            // ['nama' => 'Genki', 'deskripsi' => 'Healthy Air Fryer'],
            // ['nama' => 'Grind and Brew', 'deskripsi' => 'Coffee & Tea Maker'],
            // ['nama' => 'Okome', 'deskripsi' => 'Low Carbo Rice Cooker'],
            // ['nama' => 'Okome Maxi', 'deskripsi' => 'Low Carbo Smart Rice Cooker'],
            // ['nama' => 'Omuni', 'deskripsi' => 'Steam Oven Air Fryer'],
            // ],
            // 'Robot Vacuum Cleaner' => [
            // ['nama' => 'TOMO 2.0 LS (Pearl White)', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ['nama' => 'TOMO 2.0 LS (Titanium Grey)', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ['nama' => 'TOMO R8', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ['nama' => 'Tomo R2', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ['nama' => 'Tomo Zoom Laser', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ['nama' => 'Tomo Zoom', 'deskripsi' => 'Smart Robot Vacuum Cleaner'],
            // ],
        ];

        foreach ($subcategories as $parentName => $items) {
            // $parent = DB::table('ms_product_category_seconds')->where('name', $parentName)->first();

            // if (!$parent) {
            //     echo "Parent category '$parentName' not found. Skipping...\n";
            //     continue;
            // }

            foreach ($items as $item) {
                $firstId = (string) Str::uuid();
                $productName = $item['nama'];

                // Insert to product_category_firsts
                DB::table('ms_product_types')->insert([
                    'id' => $firstId,
                    // 'ms_product_category_second_id' => $parent->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName),
                    'description' => 'description' . $productName,
                    'image_url' => null,
                    'header_image_url' => null,
                    'created_by' => 'system',
                    'updated_by' => 'system',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'is_activated' => true,
                ]);

                // Insert to products
                $productId = (string) Str::uuid();
                $productBrandId = DB::table('ms_product_brands')->where('name', 'Umeda')->value('id');

                if (!$productBrandId) {
                    $productBrandId = (string) Str::uuid();
                    DB::table('ms_product_brands')->insert([
                        'id' => $productBrandId,
                        'name' => 'Umeda',
                        'created_by' => 'system',
                        'updated_by' => 'system',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'is_activated' => true,
                    ]);
                }


                DB::table('ms_products')->insert([
                    'id' => $productId,
                    'id_product_type' => $firstId,
                    'ms_product_brand_id' => $productBrandId,
                    'sku' => 'SKU-' . Str::upper(Str::random(8)),
                    'name' => $productName,
                    'tag' => null,
                    'selling_price' => rand(500000, 5000000),
                    'discount_persentage' => rand(5, 30),
                    'discount_value' => rand(10000, 500000),
                    'selling_price' => rand(400000, 4500000),
                    'weight' => rand(100, 5000) / 100,
                    'rating' => rand(1, 5),
                    'sold_qty' => rand(10, 100),
                    'availability' => 'in-stock',
                    'image_url' => null,
                    'highlight_image_url' => null,
                    'sync_id' => null,
                    'created_by' => 'system',
                    'updated_by' => 'system',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'is_activated' => true,
                    'ordinal' => rand(1, 10),
                    'is_new' => true,
                ]);
            }
        }
        $this->command->info('Seeder Master Product Category First Successfully! ✅');
    }
}
