<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCustomer extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'ms_customers';
    public $incrementing = false;

    // public function deliveryOrders()
    // {
    //     return $this->hasMany(DeliveryOrder::class, 'id_customer');
    // }

    public function salesOrders()
    {
        return $this->hasMany(TrSalesOrderHeader::class, 'id_customer');
    }

    public function addresses()
    {
        return $this->hasMany(MsCustomerAddress::class, 'id_customer');
    }
}
