<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Production\ProductionHistory;
use App\Models\Production\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductionOrderController extends Controller
{
    protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Retain the existing ownership convention for compatibility.
            // TODO: migrate to a real business_id if the schema supports it.
            $this->businessId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $orders = ProductionOrder::where('user_id', $this->businessId)
            ->with('product')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'LIKE', "%{$search}%")
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('product_name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('production.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::latest()->get();

        return view('production.orders.form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|unique:production_orders,order_number',
            'product_id' => 'required|exists:products,id',
            'quantity_ordered' => 'required|integer|min:1',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
            'priority' => 'nullable',
        ]);

        ProductionOrder::create([
            'user_id' => $this->businessId,
            'order_number' => $validated['order_number'],
            'product_id' => $validated['product_id'],
            'quantity_ordered' => $validated['quantity_ordered'],
            'planned_start_date' => $validated['planned_start_date'] ?? null,
            'planned_end_date' => $validated['planned_end_date'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'planned',
        ]);

        // 'planned' / 'order_created' are not supported by the current
        // production_histories.event_type ENUM, so do not log creation.
        return redirect()->route('production.orders.index')
            ->with('success', 'Production order created successfully.');
    }

    public function show($id)
    {
        $order = ProductionOrder::where('user_id', $this->businessId)
            ->with('product')
            ->findOrFail($id);

        return view('production.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = ProductionOrder::where('user_id', $this->businessId)
            ->findOrFail($id);

        $products = Product::latest()->get();

        return view('production.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_number' => [
                'required',
                Rule::unique('production_orders', 'order_number')->ignore($id),
            ],
            'product_id' => 'required|exists:products,id',
            'quantity_ordered' => 'required|integer|min:1',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
            'priority' => 'nullable',
            // Keep validation aligned with actual production_orders.status ENUM.
            'status' => 'nullable|string',
        ]);


        DB::transaction(function () use ($id, $validated) {
            $order = ProductionOrder::where('user_id', $this->businessId)
                ->lockForUpdate()
                ->findOrFail($id);

            $oldStatus = $order->status;
            $order->update($validated);
            $newStatus = $order->status;

            if ($oldStatus === $newStatus) {
                return;
            }

            // Only event types allowed by the current history ENUM.
            $eventMap = [
                'in_progress' => 'started',
                'started' => 'started',
                'paused' => 'paused',
                'resumed' => 'resumed',
                'completed' => 'completed',
                'rejected' => 'rejected',
            ];

            // A transition from paused back to in_progress is a resume.
            if ($oldStatus === 'paused' && in_array($newStatus, ['in_progress', 'started'], true)) {
                $eventType = 'resumed';
            } else {
                $eventType = $eventMap[$newStatus] ?? null;
            }

            // 'cancelled', 'planned', etc. are not mapped: do not invent
            // an event or incorrectly equate cancellation with rejection.
            if ($eventType === null) {
                return;
            }

            ProductionHistory::create([
                'user_id' => $order->user_id,
                'production_order_id' => $order->id,
                'work_order_id' => null,
                'event_type' => $eventType,
                // Status transitions do not establish actual output quantity.
                'quantity' => 0,
                'recorded_at' => now(),
            ]);
        });

        return redirect()->route('production.orders.index')
            ->with('success', 'Production order updated successfully.');
    }

    public function destroy($id)
    {
        ProductionOrder::where('user_id', $this->businessId)
            ->findOrFail($id)
            ->delete();

        return redirect()->route('production.orders.index')
            ->with('success', 'Production order deleted successfully.');
    }
}
