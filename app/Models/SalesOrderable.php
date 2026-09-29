<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class SalesOrderable extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    // Menentukan nama tabel kustom sesuai schema
    protected $table = 'sales_orderable';

    // Mengizinkan semua field diisi secara mass-assignment
    protected $guarded = [];

    public function deliveryNoteable(): MorphTo
    {
        return $this->morphTo(
            __FUNCTION__,
            'type_sales_orderable',
            'id_sales_orderable'
        );
    }

    /**
     * Relasi ke model TrServiceOrderHeader
     */
    public function trServiceOrderHeader()
    {
        return $this->belongsTo(TrServiceOrderHeader::class, 'id_service_order_header', 'id');
    }

    /**
     * Relasi ke model TrSalesOrderHeader
     */
    public function trSalesOrderHeader()
    {
        return $this->belongsTo(TrSalesOrderHeader::class, 'id_sales_order_header', 'id');
    }

    public function serviceOrderHeader()
    {
        // Sesuaikan foreign key dengan kolom yang benar-benar ada di tabel sales_orderable
        return $this->belongsTo(TrServiceOrderHeader::class, 'id_sales_orderable');
    }
}
