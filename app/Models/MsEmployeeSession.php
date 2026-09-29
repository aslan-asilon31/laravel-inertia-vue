<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MsEmployeeSession extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ms_employee_sessions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'is_logged'     => 'boolean',
        'logged_in_at'  => 'datetime',
        'logged_out_at' => 'datetime',
    ];
}
