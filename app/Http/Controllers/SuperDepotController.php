<?php

namespace App\Http\Controllers;

use App\Models\SuperDepot;
use App\Models\User;
use Illuminate\Http\Request;

class SuperDepotController extends Controller
{
    public function index()
    {
        $superDepots = SuperDepot::with('manager')
            ->withCount('depots')
            ->latest()
            ->paginate(20);

        return view(
            'distribution.super-depots.index',
            compact('superDepots')
        );
    }

    public function create()
    {
        $managers = User::orderBy('name')->get();

        return view(
            'distribution.super-depots.create',
            compact('managers')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:super_depots,code',
            'name' => 'required|string|max:150',

            'manager_id' => [
                'nullable',
                'exists:users,id',
            ],

            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'status' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $superDepot = SuperDepot::create($validated);

        $data = [
            'message' => 'Super Depot created successfully.',
            'data' => $superDepot->load('manager'),
        ];

        return redirect(route('super-depots.index'))->with($data);
    }

    public function show(SuperDepot $superDepot)
    {
        return response()->json(
            $superDepot->load([
                'manager',
                'depots.manager',
            ])
        );
    }

    public function edit(SuperDepot $superDepot)
    {
        $managers = User::orderBy('name')->get();
        return view('distribution.super-depots.edit',compact('superDepot', 'managers'));
    }

    public function update(Request $request, SuperDepot $superDepot)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:super_depots,code,' . $superDepot->id,
            'name' => 'required|string|max:150',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'status' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $superDepot->update($validated);

        $data = [
            'message' => 'Super Depot updated successfully.',
            'data' => $superDepot->fresh()->load('manager'),
        ];

        return redirect(route('super-depots.index'))->with($data);
    }

    public function destroy(SuperDepot $superDepot)
    {
        if ($superDepot->depots()->exists()) {
            return response()->json([
                'message' => 'Cannot delete Super Depot because it has depots.'
            ], 422);
        }

        $superDepot->delete();

        return response()->json([
            'message' => 'Super Depot deleted successfully.'
        ]);
    }
}
