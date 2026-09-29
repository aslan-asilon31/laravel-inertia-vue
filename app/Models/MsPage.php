<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MsPage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ms_pages';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function newUniqueId(): string
    {
        return (string) str()->orderedUuid();
    }

    /**
     * Relasi Alias ke AccessRight (Dinamai permissions agar cocok dengan Livewire Form)
     * Menghubungkan ms_pages dengan tabel access_right via foreign key ms_page_id
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(AccessRight::class, 'ms_page_id', 'id');
    }

    /**
     * Relasi Asli ke AccessRight (Standard naming)
     */
    public function accessRights(): HasMany
    {
        return $this->hasMany(AccessRight::class, 'ms_page_id', 'id');
    }
}
