<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MsProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rawProducts = [
            // --- PRODUK REGULER / ELEKTRONIK ---
            ['name' => 'Okome Maxi Low Sugar Rice Cooker', 'image_url' => 'files/products/7b95c536-8bc2-4a1d-a0f5-ee1a2b4f5f14/7b95c536-8bc2-4a1d-a0f5-ee1a2b4f5f14_product-image_2026-07-15_09-26-10.webp', 'is_gift' => false],
            ['name' => 'Air360 Smart Air Purifier', 'image_url' => 'files/products/d97c42e2-e993-4911-8019-979590d17522/d97c42e2-e993-4911-8019-979590d17522_product-image_2026-07-15_09-24-04.webp', 'is_gift' => false],
            ['name' => 'AQ750 Smart Air Purifier', 'image_url' => 'files/products/6ed1f623-e95b-48df-8873-333b4388e307/6ed1f623-e95b-48df-8873-333b4388e307_product-image_2026-07-15_09-27-35.webp', 'is_gift' => false],
            ['name' => 'UDH1500 Dehumidifier', 'image_url' => '', 'is_gift' => false],
            ['name' => 'Besu Bluetooth Speaker', 'image_url' => '', 'is_gift' => false],
            ['name' => 'U-Stik Duo Smart Cordless Vacuum', 'image_url' => '', 'is_gift' => false],
            ['name' => 'U-Stik Lite Smart Vacuum', 'image_url' => 'files/products/6ed1f623-e95b-48df-8873-333b4388e307/6ed1f623-e95b-48df-8873-333b4388e307_product-image_2026-07-15_09-27-35.webp', 'is_gift' => false],
            ['name' => 'Waku UV-C Dust Mite Vacuum', 'image_url' => 'files/products/ca2f9953-b86c-4816-bac4-8979773fc573/ca2f9953-b86c-4816-bac4-8979773fc573_product-image_2026-07-15_09-35-23.webp', 'is_gift' => false],
            ['name' => 'Genki Air Fryer', 'image_url' => 'files/products/ea072ab9-99e0-4deb-aa75-5a0dfe0050ff/ea072ab9-99e0-4deb-aa75-5a0dfe0050ff_product-image_2026-07-15_09-36-21.webp', 'is_gift' => false],
            ['name' => 'Omuni Steam Air Fryer Oven', 'image_url' => 'files/products/d1a13c70-fdcf-436d-a800-842a361ba888/d1a13c70-fdcf-436d-a800-842a361ba888_product-image_2026-07-15_09-37-13.webp', 'is_gift' => false],
            ['name' => 'Tomo R8+ Robot Vacuum', 'image_url' => '', 'is_gift' => false],
            ['name' => 'Tomo R2 Smart Robot Vacuum', 'image_url' => 'files/products/34940edb-3674-40ff-a91c-d413c11715f2/34940edb-3674-40ff-a91c-d413c11715f2_product-image_2026-07-15_09-19-26.webp', 'is_gift' => false],
            ['name' => 'Minito Mini Desktop Vacuum', 'image_url' => 'files/products/8ed24003-d0ce-4f6f-b963-e3e9525c7fe9/8ed24003-d0ce-4f6f-b963-e3e9525c7fe9_product-image_2026-07-15_09-33-59.webp', 'is_gift' => false],
            ['name' => 'DX208E Canister Vacuum', 'image_url' => '', 'is_gift' => false],

            // --- PRODUK KHUSUS MARKETING GIFT ---
            ['name' => 'Gift Box Eksklusif VIP Customer', 'image_url' => '', 'is_gift' => true],
            ['name' => 'Tumbler Stainless Steel Special Edition KOL', 'image_url' => '', 'is_gift' => true],
            ['name' => 'Pouch Kulit Souvenir Launching', 'image_url' => '', 'is_gift' => true],
            ['name' => 'Kalender Meja & Merchandise Akhir Tahun', 'image_url' => '', 'is_gift' => true],
        ];

        // Ambil referensi ID dari database terkait
        $categoryIds = DB::table('ms_product_categories')->pluck('id')->toArray();
        $typeIds = DB::table('ms_product_types')->pluck('id')->toArray();
        $giftIds = DB::table('ms_product_gift')->pluck('id')->toArray();

        $idrCurrencyId = DB::table('ms_currencies')
            ->where('code', 'IDR')
            ->orWhere('name', 'IDR')
            ->value('id') ?? (string) Str::uuid();

        $mappedData = array_map(function ($item, $index) use ($categoryIds, $typeIds, $giftIds, $idrCurrencyId, $now) {
            $productId = (string) Str::uuid();
            $currencyPriceId = (string) Str::uuid();

            // Harga untuk gift biasanya 0 atau bernilai khusus, produk reguler menggunakan acak
            $basePrice = $item['is_gift'] ? 0 : rand(500000, 5000000);
            $discountPercent = $item['is_gift'] ? 0 : rand(5, 30);
            $discountValue = ($basePrice * $discountPercent) / 100;
            $finalPrice = $basePrice - $discountValue;

            $randomCategoryId = !empty($categoryIds) ? $categoryIds[array_rand($categoryIds)] : null;
            $randomTypeId = !empty($typeIds) ? $typeIds[array_rand($typeIds)] : null;
            $randomGiftId = !empty($giftIds) ? $giftIds[array_rand($giftIds)] : null;

            return [
                'product' => [
                    'id'                  => $productId,
                    'sync_id'             => 'SYNC-' . strtoupper(Str::random(8)),
                    'id_product_gift'     => $item['is_gift'] ? $randomGiftId : null,
                    'id_product_category' => $randomCategoryId,
                    'id_product_type'     => $randomTypeId,
                    'sku'                 => ($item['is_gift'] ? 'GFT-' : 'UMD-') . strtoupper(Str::random(5)) . ($index + 1),
                    'name'                => $item['name'],
                    'selling_price'       => $basePrice,
                    'discount_persentage' => $discountPercent,
                    'discount_value'      => $discountValue,
                    'nett_price'          => $finalPrice,
                    'weight'              => rand(200, 3000) / 1000,
                    'rating'              => rand(40, 50) / 10,
                    'sold_qty'            => rand(10, 1000),
                    'availability'        => 'in-stock',
                    'image_url'           => !empty($item['image_url']) ? $item['image_url'] : null,
                    'status'              => 'terbit',
                    'highlight_image_url' => null,
                    'created_by'          => 'system',
                    'updated_by'          => 'system',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                    'ordinal'             => $index + 1,
                    'is_new'              => true,
                    'is_activated'        => 1,
                ],
                'currency_price' => [
                    'id'           => $currencyPriceId,
                    'id_product'   => $productId,
                    'id_currency'  => $idrCurrencyId,
                    'price'        => $basePrice,
                    'created_by'   => 'system',
                    'updated_by'   => 'system',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'is_activated' => 1,
                ],
                'price_purchase' => [
                    'id'                => (string) Str::uuid(),
                    'id_product'        => $productId,
                    'id_currency_price' => $currencyPriceId,
                    'ordinal'           => $index + 1,
                    'created_by'        => 'system',
                    'updated_by'        => 'system',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                    'is_activated'      => 1,
                ],
                'quotation' => [
                    'id'                => (string) Str::uuid(),
                    'id_product'        => $productId,
                    'id_currency_price' => $currencyPriceId,
                    'ordinal'           => $index + 1,
                    'created_by'        => 'system',
                    'updated_by'        => 'system',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                    'is_activated'      => 1,
                ]
            ];
        }, $rawProducts, array_keys($rawProducts));

        DB::table('ms_currency_prices')->insert(array_column($mappedData, 'currency_price'));
        DB::table('ms_products')->insert(array_column($mappedData, 'product'));
        DB::table('ms_product_price_purchases')->insert(array_column($mappedData, 'price_purchase'));
        DB::table('ms_product_price_purchase_quotations')->insert(array_column($mappedData, 'quotation'));

        $this->command->info('Bulk Insert Master Product & Gift Berhasil Dijalankan Instan! ✅');
    }
}
