<?php

namespace App\Http\Controllers;

use App\Models\MsEmployee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MsEmployeeController extends Controller
{

    public function index(Request $request)
    {

        return Inertia::render('private/ms-employee/Index', [
            'employees' => \App\Models\MsEmployee::all(),
        ]);
    }
}
