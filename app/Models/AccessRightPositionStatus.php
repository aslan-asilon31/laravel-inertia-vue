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

class AccessRightPositionStatus  extends  Pivot
{
    use HasFactory, HasUuids;
    public $table = 'pv_access_right_position_status';
    protected $guarded = [];

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    public function position()
    {
        return $this->belongsTo(MsPosition::class, 'id_access_right_position');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\MsStatus::class, 'status_id');
    }

    public function accessRightPosition()
    {
        return $this->belongsTo(AccessRightPosition::class, 'id_access_right_position');
    }
}
