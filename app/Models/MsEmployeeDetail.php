<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsEmployeeDetail extends Model
{
    use HasFactory, HasUuids;

    /**
     * Generate a new unique identifier for the model.
     *
     * @return string
     */
    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    protected $guarded = [];

    protected $keyType = 'string';

    protected $table = 'ms_employee_details';

    public $incrementing = false;


    public function employee()
    {
        return $this->belongsTo(MsEmployee::class, 'id_employee', 'id');
    }
}
