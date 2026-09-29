<?php

namespace App\Helpers\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

trait ManualActivityLog
{
    /**
     * Method universal untuk update data, catat log, dan batasi maksimal 5 log terbaru.
     */
    public function logAndUpdate(string $modelClass, string $id, string $field, $newValue): bool
    {
        // 1. Ambil data lama sebelum di-update
        $record = $modelClass::find($id);

        if (!$record) {
            return false;
        }

        $oldValue = $record->getAttribute($field);

        if ($oldValue === $newValue) {
            return true;
        }

        // 2. Ambil informasi user yang login
        $user = Auth::guard('employee')->user() ?? Auth::user();
        $userName = $user->name ?? ($this->employeeLoginName ?? 'System');

        $formattedField = ucwords(str_replace('_', ' ', $field));

        // 3. Lakukan update data ke database
        $modelClass::where('id', $id)->update([
            $field       => $newValue,
            'updated_at' => now(),
            'updated_by' => $userName
        ]);

        // 4. Buat log baru
        ActivityLog::create([
            'id'           => (string) Str::uuid(),
            'log_name'     => class_basename($modelClass),
            'description'  => "Memperbarui {$formattedField} pada " . class_basename($modelClass) . " oleh {$userName}",
            'subject_type' => $modelClass,
            'subject_id'   => $id,
            'causer_type'  => $user ? get_class($user) : null,
            'causer_id'    => $user && method_exists($user, 'getKey') ? $user->getKey() : null,
            'properties'   => [
                'field' => $field,
                'old'   => $oldValue,
                'new'   => $newValue,
            ],
        ]);

        // 5. BATASI HANYA 5 LOG TERBARU
        // Cari semua ID log berdasarkan subject terkait, urutkan dari yang terbaru
        $existingLogs = ActivityLog::where('subject_type', $modelClass)
            ->where('subject_id', $id)
            ->orderBy('created_at', 'desc')
            ->pluck('id');

        // Jika jumlah log lebih dari 5, ambil ID ke-6 dan seterusnya untuk dihapus
        if ($existingLogs->count() > 5) {
            $logsToDelete = $existingLogs->slice(5); // Ambil sisa setelah 5 data teratas
            ActivityLog::whereIn('id', $logsToDelete)->delete();
        }

        return true;
    }

    /**
     * Method yang dipanggil saat tombol Log diklik
     */
    public function openLogModal(string $id)
    {
        // Ambil data log menggunakan method dari model ActivityLog
        $this->activityLogs = \App\Models\ActivityLog::getLogsForRecord($id);

        // Buka modal (sesuaikan dengan state modal Anda, misal Mary UI pakai ->show() atau variabel boolean)
        $this->isLogModalOpen = true;

        // Jika menggunakan Mary UI Modal, Anda bisa langsung panggil dispatch/toggle atau set variable
        // Contoh jika pakai Mary UI Modal: $this->dispatch('open-modal', 'log-modal');
    }
}
