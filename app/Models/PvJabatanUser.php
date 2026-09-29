<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Helpers\Permission\Traits\HasAccess;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JabatanUser  extends  Authenticatable
{
    use HasFactory, HasUuids;
    public $table = 'position_user';
    protected $guarded = [];

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    public function hakAksesGrup()
    {
        return $this->hasMany(MsHakAksesGrup::class, 'id_access_right_grup');
    }

    public function hakAksesposition()
    {
        return $this->belongsTo(MsHakAkses::class);
    }
}
