<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseRack extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'pv_warehouse_racks';
    public $incrementing = false;

    public function msWarehouse(): HasMany
    {
        return $this->hasMany(MsWarehouse::class, 'id_warehouse', 'id');
    }

    public function msRack(): HasMany
    {
        return $this->hasMany(MsRack::class, 'id_rack', 'id');
    }
}
