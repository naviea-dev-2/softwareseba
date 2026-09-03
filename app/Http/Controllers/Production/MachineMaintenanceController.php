<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\Machine;
use App\Models\Production\MachineMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineMaintenanceController extends Controller
{
    protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;

            return $next($request);
        });
    }

    public function index()
    {
        $maintenances = MachineMaintenance::where(
            'user_id',
            $this->businessId
        )
            ->with('machine')
            ->latest()
            ->paginate(20);

        return view(
            'production.machine-maintenances.index',
            compact('maintenances')
        );
    }

    public function create()
    {
        $machines = Machine::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.machine-maintenances.create',
            compact('machines')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required',
            'maintenance_type' => 'required',
            'scheduled_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'next_due_date' => 'nullable|date',
            'description' => 'nullable',
            'downtime_hours' => 'nullable|numeric',
            'status' => 'nullable',
            'cost' => 'nullable|numeric',
        ]);

        MachineMaintenance::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.machine-maintenances.index')
            ->with('success', 'Maintenance created successfully.');
    }

    public function show($id)
    {
        $maintenance = MachineMaintenance::where(
            'user_id',
            $this->businessId
        )
            ->with('machine')
            ->findOrFail($id);

        return view(
            'production.machine-maintenances.show',
            compact('maintenance')
        );
    }

    public function edit($id)
    {
        $maintenance = MachineMaintenance::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $machines = Machine::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.machine-maintenances.edit',
            compact('maintenance', 'machines')
        );
    }

    public function update(Request $request, $id)
    {
        $maintenance = MachineMaintenance::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'machine_id' => 'required',
            'maintenance_type' => 'required',
            'scheduled_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'next_due_date' => 'nullable|date',
            'description' => 'nullable',
            'downtime_hours' => 'nullable|numeric',
            'status' => 'nullable',
            'cost' => 'nullable|numeric',
        ]);

        $maintenance->update($validated);

        return redirect()
            ->route('production.machine-maintenances.index')
            ->with('success', 'Maintenance updated successfully.');
    }

    public function destroy($id)
    {
        MachineMaintenance::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.machine-maintenances.index')
            ->with('success', 'Maintenance deleted successfully.');
    }
}