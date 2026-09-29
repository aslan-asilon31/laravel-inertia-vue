<?php

namespace App\Helpers\Traits;

use Carbon\Carbon;

trait HasTableHelpers
{
    private function getOrdinal(string $modelClass, string $cmd, ?int $ordinal): int
    {
        if ($cmd === 'edit' && $ordinal !== null) {
            return (int) $ordinal;
        }
        $max = $modelClass::max('ordinal');
        return ($max !== null) ? (int)($max + 1) : 1;
    }

    private function getOrdinalDetail(string $modelDetailClass, string $foreignKeyId, string $foreignKeyName, ?string $detailId): int
    {
        return $modelDetailClass::max('ordinal') + 1 ?? 1;
    }

    public function defaultFields(object $record): array
    {
        return [
            'ordinal'      => $record->ordinal ?? null,
            'status'       => $record->status ?? null,
            'is_activated' => $record->is_activated ?? null,
            'created_by'   => $record->created_by ?? null,
            'updated_by'   => $record->updated_by ?? null,
            'created_at'   => $record->created_at ?? null,
            'updated_at'   => $record->updated_at ?? null,
        ];
    }

    public function headerStandard(array $customHeaders = []): array
    {
        $prefixHeaders = [
            ['key' => 'select', 'label' => '', 'sortable' => false, 'class' => 'w-1'],
            ['key' => 'action', 'label' => 'Aksi', 'sortable' => false, 'class' => 'w-1 text-nowrap'],
            ['key' => 'no', 'label' => '#', 'sortable' => false, 'class' => 'w-1 text-nowrap'],
        ];

        $suffixHeaders = [
            ['key' => 'status', 'label' => 'Status', 'sortBy' => 'status'],
            ['key' => 'ordinal', 'label' => 'Ordinal', 'sortBy' => 'ordinal'],
            ['key' => 'is_activated', 'label' => 'Is Activated', 'sortBy' => 'is_activated'],
            ['key' => 'created_by', 'label' => 'Created By', 'sortBy' => 'created_by'],
            ['key' => 'updated_by', 'label' => 'Updated By', 'sortBy' => 'updated_by'],
            ['key' => 'created_at', 'label' => 'Created At', 'sortBy' => 'created_at', 'format' => ['date', 'Y-m-d H:i:s']],
            ['key' => 'updated_at', 'label' => 'Updated At', 'sortBy' => 'updated_at', 'format' => ['date', 'Y-m-d H:i:s']],
        ];

        return array_merge($prefixHeaders, $customHeaders, $suffixHeaders);
    }

    public function standardFields($formProperty, string $defaultStatus = 'terbit'): array
    {
        return [
            'ordinal'      => $formProperty->ordinal ?? 1,
            'is_activated' => $formProperty->is_activated ?? 1,
            'created_at'   => now(),
            'updated_at'   => now(),
            'created_by'   => $this->employeeLoginName ?? 'system',
            'updated_by'   => $this->employeeLoginName ?? 'system',
        ];
    }

    public function standardHeaderCreateFields($formProperty): array
    {
        return [
            'ordinal'      => $formProperty->ordinal ?? 1,
            'is_activated' => $formProperty->is_activated ?? 1,
            'created_at'   => now(),
            'updated_at'   => now(),
            'created_by'   => $this->employeeLoginName ?? 'system',
            'updated_by'   => $this->employeeLoginName ?? 'system',
        ];
    }

    public function standardHeaderUpdateFields($formProperty): array
    {
        return [
            'ordinal'      => $formProperty->ordinal ?? 1,
            'is_activated' => $formProperty->is_activated ?? 1,
            'updated_at'   => now(),
            'updated_by'   => $this->employeeLoginName ?? 'system',
        ];
    }

    public function standardDetailCreateFields($formProperty, string $defaultStatus = 'terbit'): array
    {
        $userName = $this->employeeLoginName ?? 'system';

        return [
            'id'           => (string) \Illuminate\Support\Str::uuid(),
            'ordinal'      => $formProperty->ordinal ?? 1,
            'status'       => $formProperty->status ?? $defaultStatus,
            'is_activated' => $formProperty->is_activated ?? 1,
            'created_at'   => now(),
            'updated_at'   => now(),
            'created_by'   => $userName,
            'updated_by'   => $userName,
        ];
    }

    public function standardDetailUpdateFields($formProperty): array
    {
        return [
            'ordinal'      => $formProperty->ordinal ?? 1,
            'status'       => $formProperty->status ?? 'terbit',
            'is_activated' => $formProperty->is_activated ?? 1,
            'updated_at'   => now(),
            'updated_by'   => $this->employeeLoginName ?? 'system',
        ];
    }

    public function standardFilters($query, $filterForm)
    {
        $form = is_object($filterForm) ? (array) $filterForm : $filterForm;

        return $query
            ->when($form['status'] ?? '', fn($q) => $q->where('status', 'like', "%{$form['status']}%"))
            ->when($form['remark'] ?? $form['remarks'] ?? '', fn($q) => $q->where('remark', 'like', "%{$form['remark']}%"))
            ->when($form['ordinal'] ?? '', fn($q) => $q->where('ordinal', 'like', "%{$form['ordinal']}%"))
            ->when($form['is_activated'] ?? '', fn($q) => $q->where('is_activated', 'like', "%{$form['is_activated']}%"))
            ->when(!empty($this->filters['start_date']) && !empty($this->filters['end_date']), function ($q) {
                $start = Carbon::parse($this->filters['start_date'])->startOfDay();
                $end   = Carbon::parse($this->filters['end_date'])->endOfDay();
                $q->whereBetween('created_at', [$start, $end]);
            });
    }
}
