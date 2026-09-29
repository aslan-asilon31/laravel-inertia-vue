<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{
    use HasFactory, HasUuids;

    public $table = 'access_right';
    protected $keyType = 'string';
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(AccessRightGroup::class, 'id_access_right_group');
    }

    public function position()
    {
        return $this->belongsToMany(MsPosition::class, 'pv_access_right_position', 'id_access_right', 'id_position')
            ->withPivot('id_access_right', 'id_position');
    }

    public function statuses()
    {
        return $this->belongsToMany(MsStatus::class, 'pv_access_right_position_status', 'id_access_right', 'status_id');
    }

    public function accessRightGroup()
    {
        return $this->belongsTo(AccessRightGroup::class, 'id_access_right_group');
    }

    public function action()
    {
        return $this->belongsTo(MsAction::class, 'action_id');
    }

    public function page()
    {
        return $this->belongsTo(MsPage::class, 'page_id');
    }

    public function accessRightPosition()
    {
        return $this->hasMany(AccessRightPosition::class, 'id_access_right');
    }
}
