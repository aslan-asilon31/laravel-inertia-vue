<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrPengajuanGift extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tr_pengajuan_gift';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
