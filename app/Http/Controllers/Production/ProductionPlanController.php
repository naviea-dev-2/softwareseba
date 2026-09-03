<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionPlanController extends Controller
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
        $plans = ProductionPlan::where(
            'user_id',
            $this->businessId
        )
            ->latest()
            ->paginate(20);

        return view(
            'production.production-plans.index',
            compact('plans')
        );
    }

    public function create()
    {
        return view('production.production-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_number' => 'required',
            'plan_period_start' => 'required|date',
            'plan_period_end' => 'required|date',
            'target_quantity' => 'required',
            'planned_capacity_hours' => 'nullable',
        ]);

        ProductionPlan::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.production-plans.index')
            ->with('success', 'Production plan created successfully.');
    }

    public function show($id)
    {
        $plan = ProductionPlan::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        return view(
            'production.production-plans.show',
            compact('plan')
        );
    }

    public function edit($id)
    {
        $plan = ProductionPlan::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        return view(
            'production.production-plans.edit',
            compact('plan')
        );
    }

    public function update(Request $request, $id)
    {
        $plan = ProductionPlan::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'plan_number' => 'required',
            'plan_period_start' => 'required|date',
            'plan_period_end' => 'required|date',
            'target_quantity' => 'required',
            'planned_capacity_hours' => 'nullable',
        ]);

        $plan->update($validated);

        return redirect()
            ->route('production.production-plans.index')
            ->with('success', 'Production plan updated successfully.');
    }

    public function destroy($id)
    {
        ProductionPlan::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.production-plans.index')
            ->with('success', 'Production plan deleted successfully.');
    }
}