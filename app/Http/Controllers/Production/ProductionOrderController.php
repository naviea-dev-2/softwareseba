<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Production\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionOrderController extends Controller
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

    public function index(Request $request)
    {
        $orders = ProductionOrder::where(
            'user_id',
            $this->businessId
        )
            ->with('product')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where(
                        'order_number',
                        'LIKE',
                        "%{$search}%"
                    )
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where(
                            'title',
                            'LIKE',
                            "%{$search}%"
                        );
                    });
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'production.orders.index',
            compact('orders')
        );
    }

    public function create()
    {
        $products = Product::latest()->get();

        return view(
            'production.orders.form',
            compact('products')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|unique:production_orders,order_number',
            'product_id' => 'required|exists:products,id',
            'quantity_ordered' => 'required|integer',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
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

        return redirect()
            ->route('production.production-orders.index')
            ->with('success', 'Production order created successfully.');
    }

    public function show($id)
    {
        $order = ProductionOrder::where(
            'user_id',
            $this->businessId
        )
            ->with('product')
            ->findOrFail($id);

        return view(
            'production.orders.show',
            compact('order')
        );
    }

    public function edit($id)
    {
        $order = ProductionOrder::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $products = Product::latest()->get();

        return view(
            'production.orders.edit',
            compact('order', 'products')
        );
    }

    public function update(Request $request, $id)
    {
        $order = ProductionOrder::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'order_number' => 'required|unique:production_orders,order_number,' . $id,
            'product_id' => 'required|exists:products,id',
            'quantity_ordered' => 'required|integer',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'priority' => 'nullable',
            'status' => 'nullable',
        ]);

        $order->update($validated);

        return redirect()
            ->route('production.production-orders.index')
            ->with('success', 'Production order updated successfully.');
    }

    public function destroy($id)
    {
        ProductionOrder::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.production-orders.index')
            ->with('success', 'Production order deleted successfully.');
    }
}