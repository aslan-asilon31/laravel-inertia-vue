<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeePosition extends Pivot
{
    use HasUuids;

    protected $table = 'pv_employee_position';

    public $incrementing = false;
    protected $keyType = 'string';

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    public function uniqueIds()
    {
        return ['id'];
    }
}
