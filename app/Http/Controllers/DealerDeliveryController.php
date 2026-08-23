<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DealerDelivery;
use App\Models\DealerPurchaseOrder;
use App\Models\Depot;
use App\Models\Inventory\Product;
use App\Services\DealerDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealerDeliveryController extends Controller
{
    protected $businessId;
    protected $service;

    public function __construct(DealerDeliveryService $service) {
        
        $this->service = $service;
        $this->middleware(function($request, $next) {
             $this->businessId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;
            return $next($request);
        });

    }

    public function index(Request $request)
    {
        $deliveries = DealerDelivery::with([
            'dealer',
            'depot',
            'purchaseOrder'
        ])
        ->when($request->dealer_id, function ($query) use ($request) {
            $query->where('dealer_id', $request->dealer_id);
        })
        ->when($request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->latest()
        ->paginate(20);

        $dealers = Dealer::orderBy('name')->get();

        return view(
            'distribution.dealer-deliveries.index',
            compact('deliveries', 'dealers')
        );
    }

    public function create()
    {
        $dealers = Dealer::orderBy('name')->get();

        $depots = Depot::orderBy('name')->get();

        $products = Product::where('business_type_id', $this->businessId)->orderBy('product_name')->get();

        $purchaseOrders = DealerPurchaseOrder::whereIn(
            'status',
            ['approved', 'processing']
        )
        ->latest()
        ->get();

        return view(
            'distribution.dealer-deliveries.create',
            compact(
                'dealers',
                'depots',
                'products',
                'purchaseOrders'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dealer_id' => [
                'required',
                'exists:dealers,id'
            ],

            'purchase_order_id' => [
                'nullable',
                'exists:dealer_purchase_orders,id'
            ],

            'delivery_date' => [
                'required',
                'date'
            ],

            'depot_id' => [
                'nullable',
                'exists:depots,id'
            ],

            'vehicle_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'driver_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'driver_mobile' => [
                'nullable',
                'string',
                'max:30'
            ],

            'note' => [
                'nullable',
                'string'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id'
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'items.*.unit_price' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ]);

        $delivery = $this->service->create($validated);

        return redirect()
            ->route('dealer-deliveries.show', $delivery)
            ->with('success', 'Dealer delivery created successfully.');
    }

    public function show(DealerDelivery $dealerDelivery)
    {
        $dealerDelivery->load([
            'dealer',
            'depot',
            'purchaseOrder',
            'items.product',
            'trackings.creator'
        ]);

        return view(
            'distribution.dealer-deliveries.show',
            compact('dealerDelivery')
        );
    }

    public function edit(DealerDelivery $dealerDelivery)
    {
        if ($dealerDelivery->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending delivery can be edited.'
            );
        }

        $dealers = Dealer::orderBy('name')->get();

        $depots = Depot::orderBy('name')->get();

        $products = Product::where('business_type_id', $this->businessId)->orderBy('product_name')->get();

        $purchaseOrders = DealerPurchaseOrder::latest()->get();

        $dealerDelivery->load('items');

        return view(
            'distribution.dealer-deliveries.edit',
            compact(
                'dealerDelivery',
                'dealers',
                'depots',
                'products',
                'purchaseOrders'
            )
        );
    }

    public function update(
        Request $request,
        DealerDelivery $dealerDelivery
    ) {
        if ($dealerDelivery->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending delivery can be edited.'
            );
        }

        $validated = $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'purchase_order_id' =>
                'nullable|exists:dealer_purchase_orders,id',
            'delivery_date' => 'required|date',
            'depot_id' => 'nullable|exists:depots,id',

            'vehicle_no' => 'nullable|string|max:100',
            'driver_name' => 'nullable|string|max:100',
            'driver_mobile' => 'nullable|string|max:30',

            'note' => 'nullable|string',

            'items' => 'required|array|min:1',

            'items.*.product_id' =>
                'required|exists:products,id',

            'items.*.quantity' =>
                'required|numeric|min:0.01',

            'items.*.unit_price' =>
                'nullable|numeric|min:0',
        ]);

        $this->service->update(
            $dealerDelivery,
            $validated
        );

        return redirect()
            ->route(
                'dealer-deliveries.show',
                $dealerDelivery
            )
            ->with(
                'success',
                'Dealer delivery updated successfully.'
            );
    }

    public function destroy(
        DealerDelivery $dealerDelivery
    ) {
        if ($dealerDelivery->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending delivery can be deleted.'
            );
        }

        $dealerDelivery->delete();

        return redirect()
            ->route('dealer-deliveries.index')
            ->with(
                'success',
                'Dealer delivery deleted successfully.'
            );
    }

    public function statusForm(
        DealerDelivery $dealerDelivery
    ) {
        return view(
            'distribution.dealer-deliveries.status',
            compact('dealerDelivery')
        );
    }

    public function updateStatus(
        Request $request,
        DealerDelivery $dealerDelivery
    ) {

        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,preparing,dispatched,in_transit,delivered,cancelled'
            ],

            'location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);

        $this->service->updateStatus(
            $dealerDelivery,
            $validated['status'],
            $validated['location'] ?? null,
            $validated['remarks'] ?? null
        );

        return redirect()
            ->route(
                'dealer-deliveries.show',
                $dealerDelivery
            )
            ->with(
                'success',
                'Delivery status updated.'
            );
    }
}