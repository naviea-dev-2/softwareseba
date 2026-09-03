<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
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
        $machines = Machine::where(
            'user_id',
            $this->businessId
        )
            ->latest()
            ->paginate(20);

        return view(
            'production.machines.index',
            compact('machines')
        );
    }

    public function create()
    {
        return view('production.machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'machine_code' => 'nullable',
            'type' => 'nullable',
            'model' => 'nullable',
            'capacity_per_hour' => 'nullable|numeric',
            'location' => 'nullable',
        ]);

        Machine::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.machines.index')
            ->with('success', 'Machine created successfully.');
    }

    public function show($id)
    {
        $machine = Machine::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        return view(
            'production.machines.show',
            compact('machine')
        );
    }

    public function edit($id)
    {
        $machine = Machine::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        return view(
            'production.machines.edit',
            compact('machine')
        );
    }

    public function update(Request $request, $id)
    {
        $machine = Machine::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'machine_code' => 'nullable',
            'type' => 'nullable',
            'model' => 'nullable',
            'capacity_per_hour' => 'nullable|numeric',
            'location' => 'nullable',
        ]);

        $machine->update($validated);

        return redirect()
            ->route('production.machines.index')
            ->with('success', 'Machine updated successfully.');
    }

    public function destroy($id)
    {
        Machine::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.machines.index')
            ->with('success', 'Machine deleted successfully.');
    }
}