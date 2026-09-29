<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MsProduct extends Model
{
    use HasUuids;

    protected $table = 'ms_products';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function files(): MorphMany
    {
        return $this->morphMany(MsFile::class, 'fileable_id');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(MsFile::class, 'fileable_id');
    }

    public function productGift(): BelongsTo
    {
        return $this->belongsTo(MsProductGift::class, 'id_product_gift', 'id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(MsProductCategory::class, 'id_product_category', 'id');
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(MsProductType::class, 'id_product_type', 'id');
    }

    public function productBrand(): BelongsTo
    {
        return $this->belongsTo(MsBrand::class, 'id_brand', 'id');
    }
}
