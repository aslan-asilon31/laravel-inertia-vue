<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrPengajuanGiftDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tr_pengajuan_gift_detail';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    /**
     * Relasi ke produk (ms_products)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(MsProduct::class, 'id_product', 'id');
    }
}
