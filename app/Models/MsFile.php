<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MsFile extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ms_files';

    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
        // return $this->morphTo(__FUNCTION__, 'bergambar_type', 'bergambar_id');
    }
}
