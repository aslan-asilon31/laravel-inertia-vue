<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCustomerAddress extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'ms_customer_addresses';
    public $incrementing = false;

    public function customer()
    {
        return $this->belongsTo(MsCustomer::class, 'id_customer');
    }

    // public function deliveryOrders()
    // {
    //     return $this->hasMany(DeliveryOrder::class, 'id_customer_address');
    // }
}
