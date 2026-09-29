<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'permissions';
    protected $guarded = [];

    public function action()
    {
        return $this->belongsTo(MsAction::class, 'action_id');
    }

    public function page()
    {
        return $this->belongsTo(MsPage::class, 'page_id');
    }
}
