<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MsEmployeeAccount extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'ms_employee_accounts';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_employee',
        'username',
        'name',
        'email',
        'password',
        'tgl_verifikasi_email',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tgl_verifikasi_email' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
