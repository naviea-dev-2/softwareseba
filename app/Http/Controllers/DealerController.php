<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Depot;
use App\Models\Hr\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index()
    {
        $dealers = Dealer::with(['depot.superDepot',])
            ->latest()
            ->paginate(20);

        return view(
            'distribution.dealers.index',
            compact('dealers')
        );
    }


    public function create()
    {
        $depots = Depot::where('status', 1)
            ->orderBy('name')
            ->get();

        $salesPersons = Employee::orderBy('employee_name')->where('business_id', auth()->user()->business->id)->get();

        return view(
            'distribution.dealers.create',
            compact(
                'depots',
                'salesPersons'
            )
        );
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'depot_id' => 'required|exists:depots,id',

            'code' => 'required|string|max:50|unique:dealers,code',

            'name' => 'required|string|max:150',

            'business_name' => 'nullable|string|max:200',

            'owner_name' => 'nullable|string|max:150',

            'phone' => 'required|string|max:30',

            'email' => 'nullable|email|max:100',

            'address' => 'nullable|string',

            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',

            'nid' => 'nullable|string|max:50',

            'trade_license' => 'nullable|string|max:100',

            'credit_limit' => 'nullable|numeric|min:0',

            'payment_terms' => 'nullable|string|max:100',

            'opening_balance' => 'nullable|numeric|min:0',

            'sales_person_id' => 'nullable|exists:users,id',

            'status' => 'boolean',

            'notes' => 'nullable|string',
        ]);

        $dealer = Dealer::create($validated);

         $data = [
            'message' => 'Dealer Create successfully.',
            'data' => $dealer,
        ];

        return redirect(route('dealers.index'))->with($data);
    }

    public function show(Dealer $dealer)
    {
        return response()->json(
            $dealer->load([
                'depot.superDepot',
                'salesPerson',
                'securityMoney'
            ])
        );
    }

    public function update(Request $request, Dealer $dealer)
    {
        $validated = $request->validate([
            'depot_id' => 'required|exists:depots,id',

            'code' => 'required|string|max:50|unique:dealers,code,' . $dealer->id,

            'name' => 'required|string|max:150',

            'business_name' => 'nullable|string|max:200',

            'owner_name' => 'nullable|string|max:150',

            'phone' => 'required|string|max:30',

            'email' => 'nullable|email|max:100',

            'address' => 'nullable|string',

            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',

            'nid' => 'nullable|string|max:50',

            'trade_license' => 'nullable|string|max:100',

            'credit_limit' => 'nullable|numeric|min:0',

            'payment_terms' => 'nullable|string|max:100',

            'opening_balance' => 'nullable|numeric|min:0',

            'sales_person_id' => 'nullable|exists:users,id',

            'status' => 'boolean',

            'notes' => 'nullable|string',
        ]);

        $dealer->update($validated);

         $data = [
            'message' => 'Dealer Update successfully.',
            'data' => $dealer,
        ];

        return redirect(route('dealers.index'))->with($data);
    }

    public function destroy(Dealer $dealer)
    {
        if ($dealer->securityMoney()->exists()) {
            return response()->json([
                'message' => 'Cannot delete Dealer because security money transactions exist.'
            ], 422);
        }

        $dealer->delete();

        return response()->json([
            'message' => 'Dealer deleted successfully.'
        ]);
    }
}
