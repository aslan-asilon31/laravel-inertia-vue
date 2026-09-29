<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Helpers\Permission\Traits\HasAccess;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccessRightPositionWarehouseBranch  extends  Pivot
{
    use HasFactory, HasUuids;
    public $table = 'pv_access_right_position_warehouse_branch';
    protected $guarded = [];


    public function msWarehouse()
    {
        return $this->belongsTo(MsWarehouse::class, 'id_warehouse', 'id');
    }

    public function msBranch()
    {
        return $this->belongsTo(MsBranch::class, 'id_branch', 'id');
    }
}
