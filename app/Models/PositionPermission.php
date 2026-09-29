<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionPermission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'position_permission';

    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo(MsPosition::class, 'position_id', 'id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
