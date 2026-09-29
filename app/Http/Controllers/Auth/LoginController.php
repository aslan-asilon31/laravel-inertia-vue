<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\EmployeeSession;
use App\Models\MsEmployeeAccount;
use App\Models\MsEmployeeDetail;
use Inertia\Inertia;

class LoginController extends Controller
{
    // Menampilkan Halaman Login (Menggunakan layout khusus auth/blank)
    public function create(Request $request)
    {
        $sesid = $request->input('sesid');

        if (!Auth::guard('employee')->check() && $sesid) {
            $employeeSession = EmployeeSession::where('sesid', $sesid)
                ->where('is_logged', true)
                ->first();

            if ($employeeSession) {
                $employeeAccount = MsEmployeeAccount::find($employeeSession->id_employee);
                if ($employeeAccount) {
                    Auth::guard('employee')->login($employeeAccount);
                }
            }
        }
        if (Auth::guard('employee')->check()) {
            return redirect()->route('dashboard', [
                'sesid' => $sesid,
                'cmd'   => 'list',
            ]);
        }
        return Inertia::render('private/login/Login');
    }

    // Proses Logika Login
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // 1. Cari data employee detail beserta relasi employee dan posisinya
            $employeeDetail = MsEmployeeDetail::with(['employee.positions'])
                ->where('username', $request->username)
                ->first();

            if (!$employeeDetail || !Hash::check($request->password, $employeeDetail->password)) {
                return back()->withErrors([
                    'username' => 'Username atau Password salah.',
                ])->withInput();
            }

            $employee = $employeeDetail->employee;

            if (!$employee) {
                return back()->withErrors([
                    'username' => 'Data profil karyawan tidak ditemukan.',
                ])->withInput();
            }

            $sessionToken = Str::uuid()->toString();

            // 2. Ambil posisi pertama dari relasi many-to-many
            $employeePosition = $employee->positions->first();
            $roleName = $employeePosition->name ?? '-';

            // 3. Buat sesi login karyawan
            $employeeSession = EmployeeSession::create([
                'is_logged'     => true,
                'sesid'         => $sessionToken,
                'id_employee'   => $employee->id,
                'name_employee' => $employee->name,
                'code_employee' => $employee->name . '-code',
                'role'          => $roleName,
                'logged_in_at'  => now(),
            ]);

            $inputUsernameAccount = Str::slug($employeeSession->name_employee, '-');

            $employeeAccount = MsEmployeeAccount::updateOrCreate(
                ['username' => $inputUsernameAccount],
                [
                    'id_employee'          => $employeeSession->id_employee,
                    'name'                 => $employeeSession->name_employee,
                    'username'             => $inputUsernameAccount,
                    'password'             => bcrypt($request->password),
                    'tgl_verifikasi_email' => now(),
                    'status'               => 'terbit',
                    'created_by'           => 'system',
                    'updated_by'           => 'system',
                ]
            );

            Auth::guard('employee')->login($employeeAccount);
            $request->session()->regenerate();

            // Redirect ke halaman dashboard menggunakan Inertia
            return redirect()->route('dashboard', [
                'sesid' => $employeeSession->sesid,
                'cmd'   => 'list',
            ]);
        } catch (\Throwable $e) {
            Log::error('Login gagal: ' . $e->getMessage());
            return back()->withErrors([
                'username' => 'Terjadi kesalahan sistem.',
            ]);
        }
    }

    // Proses Logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
