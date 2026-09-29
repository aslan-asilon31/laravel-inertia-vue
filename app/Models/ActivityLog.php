<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table = 'activity_logs';
    protected $guarded = ['id'];
    protected $casts = [
        'properties' => 'array',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Method khusus untuk mengambil data log berdasarkan model dan ID tertentu
     * 
     * @param string $modelClass Kelas model (misal: MsPage::class)
     * @param string $id ID dari record
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsForRecord(string $id)
    {
        return self::where('subject_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
