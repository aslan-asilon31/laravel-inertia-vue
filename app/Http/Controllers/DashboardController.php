<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('employee')->user();

        // Memanggil SidebarController untuk struktur menu hak akses
        $menuStructure = app(SidebarController::class)->getMenuStructure();

        // Data profil yang akan dipassing ke view/layout & Pinia
        $employeeData = [
            'name' => $user->name ?? 'System',
            'position' => $user->position->name ?? 'Employee',
        ];

        return Inertia::render('private/dashboard/Index', [
            'employeeName'  => $employeeData['name'],
            'user'          => $employeeData,
            'menuStructure' => $menuStructure,
        ]);
    }
}
