<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

trait HandlesPriceCalculations
{
    public static function purchasePriceSaveQuotation(
        $model,
        $inputPrice,
        $detailId,
        $quotationId,
        $msProductId,
        $selectedCurrency,
        $activeCurrencyId,
        $activeCurrency
    ): void {
        if (!$detailId) {
            throw new Exception('ID Detail transaksi tidak valid.');
        }

        if ($inputPrice === null || $inputPrice === '' || (float) $inputPrice < 0) {
            throw new Exception('Harga penawaran (Price Quotation) tidak valid.');
        }

        $currencyId = DB::table('ms_currencies')
            ->where('id', $activeCurrencyId)
            ->value('id');

        if (!$currencyId) {
            throw new Exception('Mata uang aktif tidak terdaftar.');
        }

        DB::transaction(function () use ($model, $currencyId, $inputPrice, $detailId, $quotationId, $msProductId) {
            $detailRecord = $model::where('id', $detailId)->first();

            if (!$detailRecord) {
                throw new Exception('Data detail transaksi tidak ditemukan.');
            }

            $userName = Auth::user()->name ?? 'system';
            $priceId  = (string) Str::uuid();

            \App\Models\MsCurrencyPrice::create([
                'id'           => $priceId,
                'id_product'   => $msProductId,
                'id_currency'  => $currencyId,
                'price'        => $inputPrice,
                'status'       => 'terbit',
                'is_activated' => 1,
                'created_by'   => $userName,
                'updated_by'   => $userName,
            ]);

            $purchaseQuotation = null;
            if (!empty($quotationId)) {
                $purchaseQuotation = \App\Models\MsProductPricePurchaseQuotation::find($quotationId);
            }

            if (!$purchaseQuotation) {
                $purchaseQuotation = \App\Models\MsProductPricePurchaseQuotation::query()
                    ->join('ms_currency_prices', 'ms_product_price_purchase_quotations.id_currency_price', '=', 'ms_currency_prices.id')
                    ->where('ms_product_price_purchase_quotations.id_product', $detailRecord->id_product)
                    ->where('ms_currency_prices.id_currency', $currencyId)
                    ->where('ms_product_price_purchase_quotations.is_activated', 1)
                    ->select('ms_product_price_purchase_quotations.*')
                    ->first();
            }

            if ($purchaseQuotation && !empty($quotationId)) {
                $purchaseQuotation->update([
                    'id_currency_price' => $priceId,
                    'updated_at'        => now(),
                ]);
                $targetQuotationId = $purchaseQuotation->id;
            } else {
                $newQuotation = \App\Models\MsProductPricePurchaseQuotation::create([
                    'id'                => (string) Str::uuid(),
                    'id_product'        => $detailRecord->id_product,
                    'id_currency_price' => $priceId,
                    'ordinal'           => (\App\Models\MsProductPricePurchaseQuotation::where('id_product', $detailRecord->id_product)->max('ordinal') ?? 0) + 1,
                    'is_activated'      => 1,
                ]);
                $targetQuotationId = $newQuotation->id;
            }

            $model::where('id', $detailId)
                ->update([
                    'id_product_price_purchase_quotation' => $targetQuotationId,
                    'updated_at'                          => now(),
                ]);
        });
    }

    public static function purchasePriceSaveRecommendation(
        $model,
        $detailId,
        $msPriceCurrencyId,
        $msProductId,
        $inputPrice,
        $selectedCurrency,
        $activeCurrencyId
    ): void {
        if (!$detailId) {
            throw new Exception('Detail transaksi tidak ditemukan.');
        }

        if ($inputPrice === null || $inputPrice === '' || (float) $inputPrice < 0) {
            throw new Exception('Harga rekomendasi (Price Recommendation) tidak valid.');
        }

        $currencyId = DB::table('ms_currencies')
            ->where('id', $activeCurrencyId)
            ->orWhere('code', strtoupper($activeCurrencyId))
            ->orWhere('name', $activeCurrencyId)
            ->value('id') ?? 'idr';

        if (!$currencyId) {
            throw new Exception('Mata uang aktif tidak terdaftar.');
        }

        DB::transaction(function () use ($model, $currencyId, $msProductId, $inputPrice, $detailId) {
            $detailRecord = $model::where('id', $detailId)->first();

            if (!$detailRecord) {
                throw new Exception('Data detail transaksi tidak ditemukan.');
            }

            if (!$msProductId) {
                throw new Exception('Produk pada detail transaksi tidak ditemukan.');
            }

            $userName = Auth::user()?->name ?? 'system';
            $priceId  = (string) Str::uuid();

            $msCurrencyPrice = \App\Models\MsCurrencyPrice::create([
                'id'           => $priceId,
                'id_product'   => $msProductId,
                'id_currency'  => $currencyId,
                'price'        => $inputPrice,
                'status'       => 'terbit',
                'is_activated' => 1,
                'created_by'   => $userName,
                'updated_by'   => $userName,
            ]);

            $quotationPrice = null;
            if (!empty($detailRecord->id_product_price_purchase_quotation)) {
                $quotationRecord = DB::table('ms_product_price_purchase_quotations as mppq')
                    ->join('ms_currency_prices as mcp', 'mppq.id_currency_price', '=', 'mcp.id')
                    ->where('mppq.id', $detailRecord->id_product_price_purchase_quotation)
                    ->select('mcp.price')
                    ->first();

                $quotationPrice = $quotationRecord ? (float) $quotationRecord->price : null;
            }

            $finalPrice = null;
            $newPricePurchaseId = $detailRecord->id_product_price_purchase;

            if ($quotationPrice !== null && $quotationPrice > 0 && abs((float) $inputPrice - $quotationPrice) < 0.01) {
                $finalPrice = (float) $inputPrice;

                if (empty($newPricePurchaseId)) {
                    $maxOrdinal = DB::table('ms_product_price_purchases')
                        ->where('id_product', $msProductId)
                        ->max('ordinal') ?? 0;

                    $newPricePurchaseId = (string) Str::uuid();

                    DB::table('ms_product_price_purchases')->insert([
                        'id'                => $newPricePurchaseId,
                        'id_product'        => $msProductId,
                        'id_currency_price' => $msCurrencyPrice->id,
                        'ordinal'           => $maxOrdinal + 1,
                        'is_activated'      => 1,
                        'created_by'        => $userName,
                        'updated_by'        => $userName,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }
            } else {
                $finalPrice = null;
                $newPricePurchaseId = null;
            }

            $model::where('id', $detailId)
                ->update([
                    'id_currency_price'            => $msCurrencyPrice->id,
                    'id_product'                   => $msProductId,
                    'product_price_purchase_final' => $finalPrice,
                    'id_product_price_purchase'    => $newPricePurchaseId,
                    'updated_at'                   => now(),
                ]);
        });
    }

    public static function purchasePriceSaveFinal(
        $model,
        $detailId,
        $msPriceCurrencyId,
        $msProductId,
        $inputPrice,
        $selectedCurrency,
        $activeCurrencyId
    ): void {
        if (!$detailId) {
            throw new Exception('Detail transaksi tidak ditemukan.');
        }
        $exists = $model::where('id', $detailId)->exists();

        if ($exists) {
            $model::where('id', $detailId)
                ->update([
                    'product_price_purchase_final' => $inputPrice,
                    'updated_at'                   => now(),
                ]);
        } else {
            $model::create([
                'id'                           => $detailId,
                'id_product'                   => $msProductId,
                'product_price_purchase_final' => $inputPrice,
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ]);
        }
    }
}
