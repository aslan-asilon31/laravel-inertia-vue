<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HandlesAuthentication
{
    public function loadDataBase()
    {
        $this->loadDataAuth();
        if (!$this->id && empty($this->filters['start_date']) && empty($this->filters['end_date'])) {
            $this->filters['start_date'] = \Carbon\Carbon::now()->subYear()->toDateString();
            $this->filters['end_date']   = \Carbon\Carbon::now()->addMonth()->toDateString();
        }

        if (empty($this->filter_details['start_date']) && empty($this->filter_details['end_date'])) {
            $this->filter_details['start_date'] = \Carbon\Carbon::now()->subYear()->toDateString();
            $this->filter_details['end_date']   = \Carbon\Carbon::now()->addMonth()->toDateString();
        }

        $lang = request()->input('lang', 'en');
        \Illuminate\Support\Facades\App::setLocale($lang);
        $this->getOptionStatus();
    }

    public function loadDataAuth()
    {
        $this->sesid = request()->input('sesid') ?? '';
        $this->tabId = request()->input('tab_id') ?? '';

        if (empty($this->tabId)) {
            $uniqueTabId = 'TAB-' . strtoupper(Str::random(10));
            $currentQuery = request()->input();
            $currentQuery['tab_id'] = $uniqueTabId;
            $redirectUrl = url()->current() . '?' . http_build_query($currentQuery);

            if (\Livewire\Livewire::isLivewireRequest()) {
                return redirect()->to($redirectUrl);
            }
            echo "<script>window.location.href = '{$redirectUrl}';</script>";
            exit;
        }

        if (!$this->sesid) {
            return redirect()->route('login');
        }

        $user = Auth::guard('employee')->user() ?? Auth::user();
        $employeeSession = $this->modelEmployeeSession::where('sesid', $this->sesid)
            ->where('is_logged', true)
            ->first();

        if (!$employeeSession) {
            $loginUrl = route('login');
            if (\Livewire\Livewire::isLivewireRequest()) {
                return redirect()->to($loginUrl);
            }
            echo "<script>window.location.href = '{$loginUrl}';</script>";
            exit;
        }

        $employeeAccount = $this->modelEmployeeAccount::where('id_employee', $employeeSession->id_employee)->first();

        if (!$employeeAccount) {
            return redirect()->route('login');
        }

        Auth::guard('employee')->setUser($employeeAccount);

        $employee = $this->modelEmployee::find($employeeAccount->id_employee)->first();
        $this->employeeLoginId   =  $employee->id ?? $user->id ?? '';
        $this->employeeLoginName = $employee->name ??  $user->name ?? 'System';

        $this->employeeLoginPosition = DB::table('pv_employee_position as ep')
            ->join('ms_positions as p', 'ep.id_position', '=', 'p.id')
            ->where('ep.id_employee', $employee->id)
            ->where('ep.is_activated', 1)
            ->value('p.name') ?? $employeeSession->role ?? '-';

        $this->employeeLoginWarehouse = DB::table('pv_employee_position as ep')
            ->join('pv_access_right_position as arp', 'ep.id_position', '=', 'arp.id_position')
            ->join('pv_access_right_position_warehouse as epw', 'arp.id', '=', 'epw.id_access_right_position')
            ->leftJoin('ms_warehouses as w', 'epw.id_warehouse', '=', 'w.id')
            ->where('ep.id_employee', $employee->id)
            ->where('ep.is_activated', 1)
            ->where('epw.is_activated', 1)
            ->value('w.name') ?? '-';

        $this->employeeLoginWarehouseBranch = DB::table('pv_employee_position as ep')
            ->join('pv_access_right_position as arp', 'ep.id_position', '=', 'arp.id_position')
            ->join('pv_access_right_position_warehouse as epw', 'arp.id', '=', 'epw.id_access_right_position')
            ->join('pv_access_right_position_warehouse_branch as epwb', 'epw.id', '=', 'epwb.id_access_right_position_warehouse')
            ->leftJoin('pv_branch_warehouses as bw', 'epwb.id_branch', '=', 'bw.id_branch')
            ->where('ep.id_employee', $employee->id)
            ->where('ep.is_activated', 1)
            ->where('epw.is_activated', 1)
            ->where('epwb.is_activated', 1)
            ->value('bw.name_branch') ?? '-';
    }

    public function logout()
    {
        $currentSesid = $this->sesid ?? request()->input('sesid');

        if ($currentSesid) {
            \App\Models\MsEmployeeSession::where('sesid', $currentSesid)
                ->update([
                    'is_logged'     => false,
                    'logged_out_at' => now(),
                ]);
        }
        Auth::guard('employee')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }
}
