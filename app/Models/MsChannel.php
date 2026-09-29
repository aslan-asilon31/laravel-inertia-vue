<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsChannel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ms_channels';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
