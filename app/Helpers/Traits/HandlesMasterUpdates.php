<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait HandlesMasterUpdates
{
    public function updateMasterFormIsActivated(string $modelClass, int $is_activated, string $id)
    {
        try {
            $modelClass::where('id', $id)->update(['is_activated' => $is_activated]);
            $this->success(
                title: 'Update Is Activated Success!',
                description: 'Perubahan pada form berhasil diperbarui otomatis.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update IsActivated Error: ' . $th->getMessage(), [
                'model' => $modelClass,
                'id' => $id,
                'value' => $is_activated
            ]);
        }
    }

    public function updateMasterFormStatus(string $modelClass, string $status, string $id)
    {
        try {
            $modelClass::where('id', $id)->update(['status' => $status]);
            $this->success(
                title: 'Update Status Success!',
                description: 'Perubahan pada form berhasil diperbarui otomatis.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update Status Error: ' . $th->getMessage(), ['model' => $modelClass, 'id' => $id, 'value' => $status]);
            $this->error(
                title: 'Update Status Failed!',
                description: 'Terjadi kesalahan sistem saat memperbarui status.',
                icon: 'o-x-circle',
                css: 'bg-error text-white font-extrabold'
            );
        }
    }

    public function updateMasterFormRemarks(string $modelClass, string $remarks, string $id)
    {
        try {
            $modelClass::where('id', $id)->update(['remarks' => $remarks]);
            $this->success(
                title: 'Update Remarks Success!',
                description: 'Perubahan pada form berhasil diperbarui otomatis.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update remarks Error: ' . $th->getMessage(), ['model' => $modelClass, 'id' => $id, 'value' => $remarks]);
            $this->error(
                title: 'Update Remarks Failed!',
                description: 'Terjadi kesalahan sistem saat memperbarui remarks.',
                icon: 'o-x-circle',
                css: 'bg-error text-white font-extrabold'
            );
        }
    }

    public function updateMasterFormMsWarehouseId(string $modelClass, string $id_warehouse, string $id)
    {
        $response = Http::get('https://api-kbn.com/app/api/apikbn.asp', ['api' => 'warehouse']);
        $warehouses = $response->json()['data'] ?? [];
        $selectedWarehouse = collect($warehouses)->firstWhere('id', $id_warehouse);
        $name_warehouse = $selectedWarehouse ? $selectedWarehouse['name'] : null;

        try {
            $modelClass::where('id', $id)->update([
                'id_warehouse'   => $id_warehouse,
                'name_warehouse' => $name_warehouse,
                'updated_at'     => now(),
                'updated_by'     => $this->employeeLoginName ?? 'System'
            ]);

            $this->success(
                title: 'Update Warehouse Success!',
                description: 'Data gudang berhasil diperbarui otomatis.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update Warehouse Error: ' . $th->getMessage(), ['model' => $modelClass, 'id' => $id]);
            $this->error(
                title: 'Update Warehouse Failed!',
                description: 'Terjadi kesalahan sistem saat memperbarui Warehouse.',
                icon: 'o-x-circle',
                css: 'bg-error text-white font-extrabold'
            );
        }
    }

    public function updateMasterFormMsCustomerId(string $modelClass, string $id_customer, string $id)
    {
        $customer = \App\Models\MsCustomer::find($id_customer);
        $name_customer = $customer ? $customer->name : null;

        try {
            $modelClass::where('id', $id)->update([
                'id_customer'   => $id_customer,
                'name_customer' => $name_customer,
                'updated_at'    => now(),
                'updated_by'    => $this->employeeLoginName ?? 'System'
            ]);

            $this->success(
                title: 'Update Customer Success!',
                description: 'Data pelanggan berhasil diperbarui.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update Customer Error: ' . $th->getMessage(), ['model' => $modelClass, 'id' => $id]);
            $this->error(
                title: 'Update Customer Failed!',
                description: 'Terjadi kesalahan sistem saat memperbarui pelanggan.',
                icon: 'o-x-circle',
                css: 'bg-error text-white font-extrabold'
            );
        }
    }

    public function updateMasterFormMsEmployeeId(string $modelClass, string $id_employee, string $id)
    {
        $employee = \App\Models\MsEmployee::find($id_employee);
        $name_employee = $employee ? $employee->name : null;

        try {
            $modelClass::where('id', $id)->update([
                'id_employee'   => $id_employee,
                'name_employee' => $name_employee,
                'updated_at'    => now(),
                'updated_by'    => $this->employeeLoginName ?? 'System'
            ]);

            $this->success(
                title: 'Update employee Success!',
                description: 'Data karyawan berhasil diperbarui.',
                icon: 'o-arrow-path',
                css: 'bg-success text-stone-950 font-extrabold'
            );
        } catch (\Throwable $th) {
            Log::error('Update employee Error: ' . $th->getMessage(), ['model' => $modelClass, 'id' => $id]);
            $this->error(
                title: 'Update employee Failed!',
                description: 'Terjadi kesalahan sistem saat memperbarui karyawan.',
                icon: 'o-x-circle',
                css: 'bg-error text-white font-extrabold'
            );
        }
    }
}
