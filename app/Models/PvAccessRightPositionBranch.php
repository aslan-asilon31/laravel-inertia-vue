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

class AccessRightPositionBranch  extends  Pivot
{
    use HasFactory, HasUuids;
    public $table = 'pv_access_right_position_branch';
    protected $guarded = [];

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    public function Cabang()
    {
        return $this->belongsTo(MsBranch::class, 'id_cabang', 'id');
    }
}
