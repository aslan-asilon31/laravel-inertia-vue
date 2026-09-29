<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsPriceItemHistory extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];
    protected $keyType = 'string';
    protected $table = 'ms_price_item_histories';
    public $incrementing = false;

    public function itemable()
    {
        return $this->morphTo('ms_price_item_history', 'ms_price_item_history_type', 'ms_price_item_history_id');
    }
}
