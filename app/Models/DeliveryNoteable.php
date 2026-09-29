<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DeliveryNoteable extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    // Menentukan nama tabel kustom sesuai schema
    protected $table = 'delivery_noteable';

    // Mengizinkan semua field diisi secara mass-assignment
    protected $guarded = [];

    public function deliveryNoteable(): MorphTo
    {
        return $this->morphTo(
            __FUNCTION__,
            'type_delivery_noteable',
            'id_delivery_noteable'
        );
    }

    /**
     * Relasi ke model TrServiceOrderHeader
     */
    public function trServiceOrderHeader()
    {
        return $this->belongsTo(TrServiceOrderHeader::class, 'id_service_order_header', 'id');
    }


    public function trServiceReturnHeader()
    {
        return $this->belongsTo(TrServiceReturnHeader::class, 'id_service_return_header', 'id');
    }

    public function trSalesReturnHeader()
    {
        return $this->belongsTo(TrSalesOrderHeader::class, 'id_sales_order_header', 'id');
    }
}
