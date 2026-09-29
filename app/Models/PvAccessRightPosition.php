<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PvHakAksesJabatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pv_access_right_position';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo(MsPosition::class, 'id_position');
    }

    public function accessRight()
    {
        return $this->belongsTo(AccessRight::class, 'id_access_right');
    }
}
