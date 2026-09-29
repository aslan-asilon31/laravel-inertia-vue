<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchWarehouse extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'pv_branch_warehouses';
    public $incrementing = false;

    public function msBranch(): HasMany
    {
        return $this->hasMany(MsBranch::class, 'id_branch', 'id');
    }

    public function msWarehouse(): HasMany
    {
        return $this->hasMany(MsWarehouse::class, 'id_warehouse', 'id');
    }
}
