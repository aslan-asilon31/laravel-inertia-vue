<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MsCurrency extends Model
{
    use HasUuids;

    protected $table = 'ms_currencies';
    protected $guarded = [];

    public function currencyPrices(): HasMany
    {
        return $this->hasMany(MsCurrencyPrice::class, 'id_currency', 'id');
    }
}
