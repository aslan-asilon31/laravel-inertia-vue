<?php

namespace App\Http\Controllers;

use App\Models\MsAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MsActionController extends Controller
{
    private function getSharedData()
    {
        $user = Auth::guard('employee')->user();
        return [
            'user' => $user ? [
                'name' => $user->name,
                'position' => $user->role ?? 'Employee',
            ] : null,
            'menuStructure' => app(\App\Http\Controllers\SidebarController::class)->getMenuStructure(),
        ];
    }

    public function index(Request $request)
    {
        $filters = $request->only(['id', 'name', 'status']);
        $perPage = $request->input('per_page', 20);
        $sortBy = $request->input('sort_by', 'ordinal');
        $sortDir = $request->input('sort_dir', 'desc');

        $query = MsAction::query();

        if (!empty($filters['id'])) {
            $query->where('id', 'like', "%{$filters['id']}%");
        }
        if (!empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $records = $query->orderBy($sortBy, $sortDir)->paginate($perPage)->withQueryString();

        return Inertia::render('private/ms-action/Index', array_merge($this->getSharedData(), [
            'records' => $records,
            'filters' => $filters,
            'success' => session('success'),
            'error'   => session('error'),
        ]));
    }

    public function create(Request $request)
    {
        $employeeName = Auth::guard('employee')->user()->name ?? 'System';

        $action = MsAction::create([
            'id'           => (string) Str::uuid(),
            'ordinal'      => (MsAction::max('ordinal') ?? 0) + 1,
            'status'       => 'draf',
            'is_activated' => 1,
            'created_by'   => $employeeName,
            'updated_by'   => $employeeName,
        ]);

        return redirect()->route('ms-action.edit', [
            'id'    => $action->id,
            'sesid' => $request->input('sesid'),
            'cmd'   => 'edit',
        ]);
    }

    public function edit($id, Request $request)
    {
        $action = MsAction::findOrFail($id);

        return Inertia::render('private/ms-action/Crud', array_merge($this->getSharedData(), [
            'action'        => $action,
            'cmd'           => $request->input('cmd', 'edit'),
            'isReadonly'    => false,
            'statusOptions' => [
                ['id' => 'draf', 'name' => 'Draf'],
                ['id' => 'terbit', 'name' => 'Terbit'],
                ['id' => 'batal', 'name' => 'Batal'],
            ]
        ]));
    }

    public function show($id, Request $request)
    {
        $action = MsAction::findOrFail($id);

        return Inertia::render('private/ms-action/Crud', array_merge($this->getSharedData(), [
            'action'        => $action,
            'cmd'           => 'show',
            'isReadonly'    => true,
            'statusOptions' => [
                ['id' => 'draf', 'name' => 'Draf'],
                ['id' => 'terbit', 'name' => 'Terbit'],
                ['id' => 'batal', 'name' => 'Batal'],
            ]
        ]));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'ordinal'      => 'nullable|integer',
            'status'       => 'nullable|string',
            'is_activated' => 'nullable|boolean',
        ]);

        $action = MsAction::findOrFail($id);
        $action->update(array_merge($validated, [
            'updated_by' => Auth::guard('employee')->user()->name ?? 'System'
        ]));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Data berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        MsAction::findOrFail($id)->delete();
        return redirect()->route('ms-action.list')->with('success', 'Data berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            MsAction::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Data terpilih berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
    }
}