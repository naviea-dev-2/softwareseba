<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business ? Auth::user()->business_id : 0;
            return $next($request);
        });
    }

    public function index()
    {
        $warehouses = Warehouse::withCount('stockBalances')
            ->where('business_id', $this->businessId)
            ->latest()
            ->paginate(20);

        return view('stock.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('stock.warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:0',
            'address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        $validated['business_id'] = $this->businessId;
        $validated['is_active'] = true;

        Warehouse::create($validated);

        return redirect()->route('stock.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    public function show(int $id)
    {
        $warehouse = Warehouse::with([
            'stockBalances.product',
            'stockMovements.product'
        ])
        ->where('business_id', $this->businessId)
        ->findOrFail($id);

        return view('stock.warehouses.show', compact('warehouse'));
    }

    public function edit(int $id)
    {
        $warehouse = Warehouse::where('business_id', $this->businessId)
            ->findOrFail($id);

        return view('stock.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, int $id)
    {
        $warehouse = Warehouse::where('business_id', $this->businessId)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:0',
            'address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        $warehouse->update($validated);

        return redirect()->route('stock.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(int $id)
    {
        $warehouse = Warehouse::where('business_id', $this->businessId)
            ->findOrFail($id);

        if ($warehouse->stockBalances()
            ->where('total_qty', '>', 0)
            ->exists()
        ) {
            return back()->with(
                'error',
                'Cannot delete warehouse containing stock.'
            );
        }

        $warehouse->delete();

        return redirect()->route('stock.warehouses.index')
            ->with('success', 'Warehouse deleted.');
    }

    public function toggleStatus(int $id)
    {
        $warehouse = Warehouse::where('business_id', $this->businessId)
            ->findOrFail($id);

        $warehouse->update([
            'is_active' => !$warehouse->is_active
        ]);

        return back()->with('success', 'Warehouse status updated.');
    }

    public function distribution()
    {
        $warehouses = Warehouse::with(['stockBalances'])
            ->where('business_id', $this->businessId)
            ->get();

        return view('stock.warehouses.distribution', compact('warehouses'));
    }
}