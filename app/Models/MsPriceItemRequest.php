<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsPriceItemRequest extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'ms_price_item_requests';
    public $incrementing = false;

    public function itemable()
    {
        return $this->morphTo('ms_price_item_request', 'ms_price_item_request_type', 'ms_price_item_request_id');
    }
}
