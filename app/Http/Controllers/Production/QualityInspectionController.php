<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ProductionOrder;
use App\Models\Production\QualityInspection;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualityInspectionController extends Controller
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
        $inspections = QualityInspection::where(
            'user_id',
            $this->businessId
        )
            ->with([
                'productionOrder:id,order_number,product_id',
                'productionOrder.product:id,title',
                'workOrder:id,work_order_number',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'production.quality-inspections.index',
            compact('inspections')
        );
    }

    public function create()
    {
        $productionOrders = ProductionOrder::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $workOrders = WorkOrder::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.quality-inspections.create',
            compact('productionOrders', 'workOrders')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_type' => 'required',
            'inspected_quantity' => 'required|numeric',
            'production_order_id' => 'nullable',
            'work_order_id' => 'nullable',
            'passed_quantity' => 'nullable|numeric',
            'failed_quantity' => 'nullable|numeric',
            'inspection_date' => 'nullable|date',
            'status' => 'nullable',
        ]);

        QualityInspection::create([
            'user_id' => $this->businessId,
            ...$validated,
            'passed_quantity' => $validated['passed_quantity'] ?? 0,
            'failed_quantity' => $validated['failed_quantity'] ?? 0,
            'status' => $validated['status'] ?? 'pending',
        ]);

        return redirect()
            ->route('production.quality-inspections.index')
            ->with('success', 'Inspection created successfully.');
    }

    public function show($id)
    {
        $inspection = QualityInspection::where(
            'user_id',
            $this->businessId
        )
            ->with([
                'productionOrder',
                'workOrder',
            ])
            ->findOrFail($id);

        return view(
            'production.quality-inspections.show',
            compact('inspection')
        );
    }

    public function edit($id)
    {
        $inspection = QualityInspection::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $productionOrders = ProductionOrder::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        $workOrders = WorkOrder::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.quality-inspections.edit',
            compact('inspection', 'productionOrders', 'workOrders')
        );
    }

    public function update(Request $request, $id)
    {
        $inspection = QualityInspection::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'inspection_type' => 'required',
            'inspected_quantity' => 'required|numeric',
            'production_order_id' => 'nullable',
            'work_order_id' => 'nullable',
            'passed_quantity' => 'nullable|numeric',
            'failed_quantity' => 'nullable|numeric',
            'inspection_date' => 'nullable|date',
            'status' => 'nullable',
        ]);

        $inspection->update($validated);

        return redirect()
            ->route('production.quality-inspections.index')
            ->with('success', 'Inspection updated successfully.');
    }

    public function destroy($id)
    {
        QualityInspection::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.quality-inspections.index')
            ->with('success', 'Inspection deleted successfully.');
    }
}