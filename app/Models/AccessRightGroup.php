<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Helpers\Permission\Traits\HasAccess;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessRightGroup  extends  Model
{
    use HasFactory, HasUuids;
    public $table = 'access_right_group';
    protected $guarded = [];
    protected $keyType = 'string';

    public function accessRight()
    {
        return $this->hasMany(AccessRight::class, 'id_access_right_group', 'id');
    }

    public function accessRights(): HasMany
    {
        return $this->hasMany(AccessRight::class, 'id_access_right_group', 'id');
    }

    public function status()
    {
        return $this->belongsTo(MsStatus::class, 'status_id');
    }
}
