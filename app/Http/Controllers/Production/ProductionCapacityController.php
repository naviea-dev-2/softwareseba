<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Production\Machine;
use App\Models\Production\ProductionCapacity;
use App\Models\Production\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionCapacityController extends Controller
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
        $capacities = ProductionCapacity::where(
            'user_id',
            $this->businessId
        )
            ->latest()
            ->paginate(20);

        return view(
            'production.production-capacities.index',
            compact('capacities')
        );
    }

    public function create()
    {
        $machines = Machine::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $workers = Worker::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $products = Product::latest()->get();

        return view(
            'production.production-capacities.create',
            compact('machines', 'workers', 'products')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift' => 'required',
            'machine_id' => 'nullable',
            'worker_id' => 'nullable',
            'product_id' => 'nullable',
            'max_units_per_hour' => 'nullable|numeric',
            'efficiency_rate' => 'nullable|numeric',
        ]);

        ProductionCapacity::create([
            'user_id' => $this->businessId,
            ...$validated,
            'efficiency_rate' => $validated['efficiency_rate'] ?? 100,
        ]);

        return redirect()
            ->route('production.production-capacities.index')
            ->with('success', 'Production capacity created successfully.');
    }

    public function show($id)
    {
        $capacity = ProductionCapacity::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        return view(
            'production.production-capacities.show',
            compact('capacity')
        );
    }

    public function edit($id)
    {
        $capacity = ProductionCapacity::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $machines = Machine::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $workers = Worker::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $products = Product::latest()->get();

        return view(
            'production.production-capacities.edit',
            compact('capacity', 'machines', 'workers', 'products')
        );
    }

    public function update(Request $request, $id)
    {
        $capacity = ProductionCapacity::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'shift' => 'required',
            'machine_id' => 'nullable',
            'worker_id' => 'nullable',
            'product_id' => 'nullable',
            'max_units_per_hour' => 'nullable|numeric',
            'efficiency_rate' => 'nullable|numeric',
        ]);

        $capacity->update($validated);

        return redirect()
            ->route('production.production-capacities.index')
            ->with('success', 'Production capacity updated successfully.');
    }

    public function destroy($id)
    {
        ProductionCapacity::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.production-capacities.index')
            ->with('success', 'Production capacity deleted successfully.');
    }
}