<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MsCurrencyPrice extends Model
{
    use HasUuids;

    protected $table = 'ms_currency_prices';
    protected $guarded = [];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(MsCurrency::class, 'id_currency', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(MsProduct::class, 'id_currency_price', 'id');
    }

    public function pricePurchases(): HasMany
    {
        return $this->hasMany(MsProductPricePurchase::class, 'id_currency_price', 'id');
    }

    public function purchaseRequestDetails(): HasMany
    {
        return $this->hasMany(TrPurchaseRequestDetail::class, 'id_currency_price', 'id');
    }
}
