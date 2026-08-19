<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealerPurchaseOrderRequest;
use App\Http\Requests\UpdateDealerPurchaseOrderRequest;
use App\Models\Dealer;
use App\Models\DealerPurchaseOrder;
use App\Models\Depot;
use App\Models\Inventory\Product;
use App\Services\DealerPurchaseOrderService;
use Illuminate\Http\Request;

class DealerPurchaseOrderController extends Controller
{
    protected $businessId;
    protected $service;

    public function __construct(DealerPurchaseOrderService $service) {
        
        $this->service = $service;
        $this->middleware(function($request, $next) {
            $this->businessId = auth()->user()->business ? auth()->user()->business->business_type_id : 0;
            return $next($request);
        });

    }


    public function index(Request $request)
    {
        $purchaseOrders = DealerPurchaseOrder::with([
            'dealer',
            'depot',
            'createdBy',
        ])
        ->when(
            $request->status,
            fn ($query, $status) =>
                $query->where('status', $status)
        )
        ->when(
            $request->dealer_id,
            fn ($query, $dealerId) =>
                $query->where('dealer_id', $dealerId)
        )
        ->latest('id')
        ->paginate(20)
        ->withQueryString();

        $dealers = Dealer::orderBy('name')->get();

        return view('distribution.dealer-purchase-orders.index', compact('purchaseOrders','dealers'));
    }


    public function create()
    {
        $dealers = Dealer::where('status', 1)
            ->with('depot')
            ->orderBy('name')
            ->get();

        $depots = Depot::where('status', 1)
            ->orderBy('name')
            ->get();

        $products = Product::where('business_type_id', $this->businessId)->orderBy('product_name')->get();

        return view(
            'distribution.dealer-purchase-orders.create',
            compact(
                'dealers',
                'depots',
                'products'
            )
        );
    }


    public function store(
        StoreDealerPurchaseOrderRequest $request
    ) {

        try {

            $po = $this->service->create(
                $request->validated(),
                auth()->user()->id
            );

            return redirect()
                ->route(
                    'dealer-purchase-orders.show',
                    $po
                )
                ->with(
                    'success',
                    'Dealer Purchase Order created successfully.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Failed to create purchase order.'
                );
        }
    }


    public function show(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        $dealerPurchaseOrder->load([
            'dealer.depot.superDepot',
            'depot',
            'items.product',
            'createdBy',
            'approvedBy',
            'histories.createdBy',
        ]);

        return view(
            'distribution.dealer-purchase-orders.show',
            compact('dealerPurchaseOrder')
        );
    }


    public function edit(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        if ($dealerPurchaseOrder->status !== 'draft') {

            return redirect()
                ->route(
                    'dealer-purchase-orders.show',
                    $dealerPurchaseOrder
                )
                ->with(
                    'error',
                    'Only draft PO can be edited.'
                );
        }

        $dealerPurchaseOrder->load('items');

        $dealers = Dealer::where('status', 1)
            ->orderBy('name')
            ->get();

        $depots = Depot::where('status', 1)
            ->orderBy('name')
            ->get();

        $products = Product::where('business_type_id', $this->businessId)->orderBy('product_name')->get();

        return view(
            'distribution.dealer-purchase-orders.edit',
            compact(
                'dealerPurchaseOrder',
                'dealers',
                'depots',
                'products'
            )
        );
    }


    public function update(
        UpdateDealerPurchaseOrderRequest $request,
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        try {

            $po = $this->service->update(
                $dealerPurchaseOrder,
                $request->validated(),
                auth()->user()->id
            );

            return redirect()
                ->route(
                    'dealer-purchase-orders.show',
                    $po
                )
                ->with(
                    'success',
                    'Dealer Purchase Order updated successfully.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    public function destroy(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        if (!in_array(
            $dealerPurchaseOrder->status,
            ['draft', 'rejected']
        )) {

            return back()
                ->with(
                    'error',
                    'This PO cannot be deleted.'
                );
        }

        $dealerPurchaseOrder->delete();

        return redirect()
            ->route('dealer-purchase-orders.index')
            ->with(
                'success',
                'Purchase Order deleted successfully.'
            );
    }


    public function submit(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        try {

            $this->service->submit(
                $dealerPurchaseOrder,
                auth()->user()->id
            );

            return back()
                ->with(
                    'success',
                    'Purchase Order submitted for approval.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    public function approve(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        try {

            $this->service->approve(
                $dealerPurchaseOrder,
                auth()->user()->id
            );

            return back()
                ->with(
                    'success',
                    'Purchase Order approved successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    public function reject(
        Request $request,
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        $request->validate([
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {

            $this->service->reject(
                $dealerPurchaseOrder,
                auth()->user()->id,
                $request->note
            );

            return back()
                ->with(
                    'success',
                    'Purchase Order rejected.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    public function cancel(
        DealerPurchaseOrder $dealerPurchaseOrder
    ) {

        try {

            $this->service->cancel(
                $dealerPurchaseOrder,
                auth()->user()->id
            );

            return back()
                ->with(
                    'success',
                    'Purchase Order cancelled.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}