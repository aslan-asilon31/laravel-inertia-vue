<?php

namespace App\Helpers\Traits;

trait HasMasterOptions
{
    public function getOptionMsEmployee(): void
    {
        $this->msEmployeeOptions = \App\Models\MsEmployee::query()
            ->whereHas('positions', fn($q) => $q->where('name', '!=', 'supplier'))
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($employee) => ['id' => $employee->id, 'name' => $employee->name])
            ->toArray();
    }

    public function getOptionMsGroundRules(): void
    {
        $this->opsiGroundRulesCategory = \App\Models\MsGroundRules::query()
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($groundRule) => ['id' => $groundRule->id, 'name' => $groundRule->name])
            ->toArray();
    }

    public function getOptionMsCategoryPengajuan(): void
    {
        $this->opsiPengajuanCategory = \App\Models\MsCategoryPengajuan::query()
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($pengajuanCategory) => ['id' => $pengajuanCategory->id, 'name' => $pengajuanCategory->name])
            ->toArray();
    }

    public function getOptionMsChannel(): void
    {
        $this->opsiChannelCategory = \App\Models\MsChannel::query()
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($channel) => ['id' => $channel->id, 'name' => $channel->name])
            ->toArray();
    }

    public function getOptionMsSpecialCase(): void
    {
        $this->opsiSpecialCaseCategory = \App\Models\MsSpecialCase::query()
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($specialCase) => ['id' => $specialCase->id, 'name' => $specialCase->name])
            ->toArray();
    }

    public function getOptionMsSupplier(): void
    {
        $this->msSupplierOptions = \App\Models\MsEmployee::query()
            ->whereHas('positions', fn($q) => $q->where('name', 'supplier'))
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(fn($supplier) => ['id' => $supplier->id, 'name' => $supplier->name])
            ->toArray();
    }

    public function getOptionMsCustomer(): void
    {
        $this->msCustomerOptions = \App\Models\MsCustomer::query()
            ->orderBy('name')
            ->get()
            ->map(fn($customer) => ['id' => $customer->id, 'name' => $customer->name])
            ->toArray();
    }

    public function getOptionCurrency(): void
    {
        $this->currencyOptions = \App\Models\MsCurrency::query()
            ->orderBy('code')
            ->get()
            ->map(fn($currency) => ['id' => $currency->id, 'name' => $currency->code])
            ->toArray();
    }

    public function getOptionMsProduct(): void
    {
        $this->msProductOptions = \App\Models\MsProduct::query()
            ->orderBy('name')
            ->get()
            ->map(fn($product) => ['id' => $product->id, 'name' => $product->name])
            ->toArray();
    }

    public function getOptionMsWarehouse(): void
    {
        $this->msWarehouseOptions = \App\Models\MsWarehouse::query()
            ->orderBy('name')
            ->get()
            ->map(fn($warehouse) => ['id' => $warehouse->id, 'name' => $warehouse->name])
            ->toArray();
    }

    public function getOptionMsBranch(): void
    {
        $this->msBranchOptions = \App\Models\MsBranch::query()
            ->orderBy('name')
            ->get()
            ->map(fn($branch) => ['id' => $branch->id, 'name' => $branch->name])
            ->toArray();
    }

    public function getOptionStatus(): void
    {
        $this->statusOptions = [
            ['id' => 'draf', 'name' => 'Draf'],
            ['id' => 'terbit', 'name' => 'Terbit'],
            ['id' => 'batal', 'name' => 'Batal'],
        ];
    }

    public function getOptionStatusPriority(): void
    {
        $this->statusPriorityOptions = [
            ['id' => 'normal', 'name' => 'Normal'],
            ['id' => 'high', 'name' => 'High'],
        ];
    }

    public function getOptionStatusRequest(): void
    {
        $this->statusPriorityOptions = [
            ['id' => 'No', 'name' => 'NO'],
            ['id' => 'Yes', 'name' => 'YES'],
        ];
    }

    public function getOptionStatusApprove(): void
    {
        $this->statusApproveOptions = [
            ['id' => 'No', 'name' => 'NO'],
            ['id' => 'Yes', 'name' => 'YES'],
        ];
    }


    public function opsiAccessRight(): void
    {
        $this->opsiAccessRight  = $this->modelAccessRight::query()
            ->orderBy('name')
            ->get()
            ->map(function ($hakAkses) {
                return [
                    'id'   => $hakAkses->id,
                    'name' => $hakAkses->name,
                ];
            })
            ->toArray();
    }

    public function opsiAccessRightGroup(): void
    {
        $this->opsiAccessRightGroup  = $this->modelAccessRightGroup::query()
            ->orderBy('name')
            ->get()
            ->map(function ($hakAksesGrup) {
                return [
                    'id'   => $hakAksesGrup->id,
                    'name' => $hakAksesGrup->name,
                ];
            })
            ->toArray();
    }


    public function opsiAccessRightGrupCategory(): void
    {
        $this->opsiAccessRightGroupCategory = $this->modelAccessRightGroup::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(function ($category) {
                return [
                    'id'   => $category,
                    'name' => ucfirst($category),
                ];
            })
            ->toArray();
    }
}
