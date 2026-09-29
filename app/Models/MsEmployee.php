<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MsEmployee extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;



    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'ms_employees';
    public $incrementing = false;


    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    public function uniqueIds()
    {
        return ['id'];
    }



    public function employeeDetail()
    {
        return $this->hasOne(MsEmployeeDetail::class);
    }

    public function position()
    {
        return $this->belongsTo(MsPosition::class, 'id_position', 'id');
    }

    public function positions()
    {
        return $this->belongsToMany(
            MsPosition::class,
            'pv_employee_position',
            'id_employee',
            'id_position'
        )->withPivot(['id', 'created_by', 'updated_by', 'status', 'ordinal'])
            ->using(\App\Models\EmployeePosition::class);
    }
}
