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

class AccessRightPosition  extends  Pivot
{
    use HasFactory, HasUuids;
    public $table = 'pv_access_right_position';
    protected $guarded = [];


    public function position()
    {
        return $this->belongsTo(MsPosition::class);
    }




    public function accessRight()
    {
        return $this->belongsToMany(
            \App\Models\AccessRight::class,
            'pv_access_right_position_status',       // pivot table
            'id_access_right_position',            // foreign key on pivot for this model
            'status_id'                       // foreign key on pivot for related model
        )->withPivot(['id', 'created_by', 'updated_by', 'created_at', 'updated_at']);
    }



    public function accessRightPositionStatuses()
    {
        // return $this->hasMany(AccessRightPositionStatus::class);
        return $this->hasMany(AccessRightPositionStatus::class, 'id_access_right_position');
    }

    public function statuses()
    {
        return $this->hasManyThrough(
            MsStatus::class,
            AccessRightPositionStatus::class,
            'id_access_right_position', // foreign key di tabel AccessRightPositionStatus
            'id', // foreign key di tabel Status
            'id', // local key di tabel AccessRightPosition
            'status_id' // foreign key di tabel AccessRightPositionStatus yang menghubungkan ke Status
        );
    }
}
