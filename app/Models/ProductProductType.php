<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductProductType extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'product_product_types';
    public $incrementing = false;

    public function msProduct(): HasMany
    {
        return $this->hasMany(MsProduct::class, 'id_product', 'id');
    }

    public function msProductType(): HasMany
    {
        return $this->hasMany(MsProductType::class, 'id_product_type', 'id');
    }
}
