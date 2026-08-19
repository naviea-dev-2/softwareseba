<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Hr\Employee;
use App\Models\SuperDepot;
use App\Models\User;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    public function index()
    {
        $depots = Depot::with([
            'superDepot',
            'manager'
        ])
            ->withCount('dealers')
            ->latest()
            ->paginate(20);

        return view(
            'distribution.depots.index',
            compact('depots')
        );
    }

    public function create()
    {
        $superDepots = SuperDepot::where('status', 1)
            ->orderBy('name')
            ->get();

        $managers = Employee::orderBy('employee_name')->where('business_id', auth()->user()->business->id)->get();

        return view(
            'distribution.depots.create',
            compact(
                'superDepots',
                'managers'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'super_depot_id' => 'required|exists:super_depots,id',

            'code' => 'required|string|max:50|unique:depots,code',

            'name' => 'required|string|max:150',

            'manager_id' => 'nullable|exists:users,id',

            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',

            'address' => 'nullable|string',

            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',

            'status' => 'boolean',

            'notes' => 'nullable|string',
        ]);

        $depot = Depot::create($validated);

         $data = [
            'message' => 'Depot created successfully.',
            'data' => $depot->load('manager'),
        ];

        return redirect(route('depots.index'))->with($data);
    }


     public function edit(Depot $depot)
    {
        $managers = User::orderBy('name')->get();
        $superDepots = SuperDepot::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('distribution.depots.edit',compact('depot', 'managers','superDepots'));
    }

    public function show(Depot $depot)
    {
        return response()->json(
            $depot->load([
                'superDepot',
                'manager',
                'dealers'
            ])
        );
    }

    public function update(Request $request, Depot $depot)
    {
        $validated = $request->validate([
            'super_depot_id' => 'required|exists:super_depots,id',

            'code' => 'required|string|max:50|unique:depots,code,' . $depot->id,

            'name' => 'required|string|max:150',

            'manager_id' => 'nullable|exists:users,id',

            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',

            'address' => 'nullable|string',

            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',

            'status' => 'boolean',

            'notes' => 'nullable|string',
        ]);

        $depot->update($validated);

        $data = [
            'message' => 'Depot Update successfully.',
            'data' => $depot->load('manager'),
        ];

        return redirect(route('depots.index'))->with($data);
    }

    public function destroy(Depot $depot)
    {
        if ($depot->dealers()->exists()) {
            return response()->json([
                'message' => 'Cannot delete Depot because it has dealers.'
            ], 422);
        }

        $depot->delete();

        return response()->json([
            'message' => 'Depot deleted successfully.'
        ]);
    }
}
