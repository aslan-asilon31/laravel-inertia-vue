<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MsPosition extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids, HasFactory;

    protected $table = 'ms_positions';

    protected $guarded = [];

    public function action()
    {
        return $this->belongsTo(\App\Models\MsAction::class, 'action_id');
    }

    public function page()
    {
        return $this->belongsTo(\App\Models\MsPage::class, 'page_id');
    }
}
