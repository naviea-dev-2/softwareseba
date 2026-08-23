<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\WarehouseTransfer;
use App\Models\Inventory\Product;
use App\Models\Stock\StockBalance;
use App\Models\Stock\StockMovement;
use App\Models\Stock\StockReservation;
use App\Models\Stock\Warehouse;
use App\Services\StockBalanceService;
use App\Services\StockReservationService;
use App\Services\WarehouseTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    protected $businessId;

    public function __construct(
        protected StockBalanceService $stockService,
        protected StockReservationService $reservationService,
        protected WarehouseTransferService $transferService
    ) {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business ? Auth::user()->business_id : 0;
            return $next($request);
        });
    }

    public function index()
    {
        return view('stock.index');
    }

    public function dashboard()
    {
        $b = $this->businessId;

        // ─── Stats Cards ───
        $totalSkus = StockBalance::where('business_id', $b)
            ->distinct('product_id')
            ->count('product_id');

        $lowStock = StockBalance::where('business_id', $b)
            ->whereColumn('available_qty', '<=', 'reorder_point')
            ->where('available_qty', '>', 0)
            ->count();

        $criticalStock = StockBalance::where('business_id', $b)
            ->where('available_qty', '<=', 0)
            ->count();

        $reservedStock = StockBalance::where('business_id', $b)
            ->sum('reserved_qty');

        $pendingTransfers = WarehouseTransfer::where('business_id', $b)
            ->whereIn('status', ['pending', 'approved', 'in_transit'])
            ->count();

        $totalMovements = StockMovement::where('business_id', $b)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ─── Tab: Stock Ledger ───
        $ledger = StockBalance::with(['product', 'warehouse'])
            ->where('business_id', $b)
            ->latest()
            ->limit(50)
            ->get();

        // ─── Tab: Reservations ───
        $reservations = StockReservation::with(['product', 'warehouse'])
            ->where('business_id', $b)
            ->latest()
            ->limit(50)
            ->get();

        // ─── Tab: Movements ───
        $movements = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('business_id', $b)
            ->latest()
            ->limit(50)
            ->get();

        // ─── Tab: Transfers ───
        $transfers = WarehouseTransfer::with(['fromWarehouse', 'toWarehouse', 'items.product'])
            ->where('business_id', $b)
            ->latest()
            ->limit(50)
            ->get();

        // ─── Tab: Warehouses ───
        $warehouses = Warehouse::where('business_id', $b)
            ->get()
            ->map(function ($wh) use ($b) {
                $wh->skus_count = StockBalance::where('business_id', $b)
                    ->where('warehouse_id', $wh->id)
                    ->distinct('product_id')
                    ->count('product_id');
                $wh->total_qty = StockBalance::where('business_id', $b)
                    ->where('warehouse_id', $wh->id)
                    ->sum('available_qty');
                return $wh;
            });

        // ─── Tab: Analytics ───
        $topProducts = StockMovement::select('product_id', DB::raw('SUM(qty) as total_moved'))
            ->with('product')
            ->where('business_id', $b)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('product_id')
            ->orderByDesc('total_moved')
            ->limit(10)
            ->get();

        return view('stock.index', compact(
            'totalSkus',
            'lowStock',
            'criticalStock',
            'reservedStock',
            'pendingTransfers',
            'totalMovements',
            'ledger',
            'reservations',
            'movements',
            'transfers',
            'warehouses',
            'topProducts'
        ));
    }

    public function ledger(Request $request)
    {
        $balances = $this->stockService->getLedger(
            $this->businessId,
            $request->all()
        );

        $warehouses = Warehouse::where('business_id', $this->businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock.ledger', compact('balances', 'warehouses'));
    }

    public function productHistory(int $productId, int $warehouseId)
    {
        $product = Product::findOrFail($productId);

        $warehouse = Warehouse::where('business_id', $this->businessId)
            ->findOrFail($warehouseId);

        $movements = $this->stockService->getProductHistory(
            $this->businessId,
            $productId,
            $warehouseId,
            100
        );

        return view('stock.history', compact('product', 'warehouse', 'movements'));
    }

    public function adjustmentForm()
    {
        $warehouses = Warehouse::where('business_id', $this->businessId)
            ->where('is_active', true)
            ->get();

        $products = Product::orderBy('product_name')->get();

        return view('stock.adjust', compact('warehouses', 'products'));
    }

    public function adjustStock(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'type' => 'required|in:add,remove',
            'qty' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->stockService->adjustStock(
                $this->businessId,
                $validated
            );

            return redirect()->route('stock.ledger')
                ->with('success', 'Stock adjusted successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function reservations(Request $request)
    {
        $reservations = $this->reservationService->getReservations(
            $this->businessId,
            $request->all()
        );

        return view('stock.reservations.index', compact('reservations'));
    }

    public function createReservation()
    {
        $products = Product::orderBy('product_name')->get();

        $warehouses = Warehouse::where('business_id', $this->businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock.reservations.create', compact('products', 'warehouses'));
    }

    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
            'order_number' => 'nullable|string|max:100',
            'customer_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date',
        ]);

        try {
            $this->reservationService->createReservation(
                $this->businessId,
                $validated
            );

            return redirect()->route('stock.reservations')
                ->with('success', 'Stock reservation created.');
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function fulfillReservation(int $id)
    {
        try {
            $this->reservationService->fulfill(
                $this->businessId,
                $id
            );

            return back()->with('success', 'Reservation fulfilled.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancelReservation(Request $request, int $id)
    {
        $request->validate([
            'cancel_reason' => 'nullable|string|max:500'
        ]);

        try {
            $this->reservationService->cancel(
                $this->businessId,
                $id,
                $request->cancel_reason
            );

            return back()->with('success', 'Reservation cancelled.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('business_id', $this->businessId);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        return view('stock.movements', [
            'movements' => $query->latest()->paginate(30)->withQueryString(),
            'warehouses' => Warehouse::where('business_id', $this->businessId)->get(),
        ]);
    }

    public function analytics()
    {
        $businessId = $this->businessId;

        $lowStockItems = $this->stockService->getLowStockItems($businessId);
        $topMovingProducts = $this->stockService->getTopMovingProducts($businessId);
        $stockValueByWarehouse = $this->stockService->getStockValueByWarehouse($businessId);

        return view('stock.analytics', compact(
            'lowStockItems',
            'topMovingProducts',
            'stockValueByWarehouse'
        ));
    }

    public function transfers(Request $request)
    {
        $transfers = $this->transferService->getTransfers(
            $this->businessId,
            $request->all()
        );

        return view('stock.transfers.index', compact('transfers'));
    }

    public function createTransfer()
    {
        $warehouses = Warehouse::where('business_id', $this->businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::orderBy('product_name')->get();

        return view('stock.transfers.create', compact('warehouses', 'products'));
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|integer',
            'to_warehouse_id' => 'required|integer',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            $this->transferService->createTransfer(
                $this->businessId,
                $validated
            );

            return redirect()->route('stock.transfers')
                ->with('success', 'Transfer request created.');
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function approveTransfer(int $id)
    {
        try {
            $this->transferService->approveTransfer(
                $this->businessId,
                $id
            );

            return back()->with('success', 'Transfer approved.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function shipTransfer(Request $request, int $id)
    {
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:255',
        ]);

        try {
            $this->transferService->shipTransfer(
                $this->businessId,
                $id,
                $validated
            );

            return back()->with('success', 'Transfer shipped successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function receiveForm(int $id)
    {
        $transfer = WarehouseTransfer::with([
            'items.product',
            'fromWarehouse',
            'toWarehouse'
        ])
            ->where('business_id', $this->businessId)
            ->findOrFail($id);

        return view('stock.transfers.receive', compact('transfer'));
    }

    public function receiveTransfer(Request $request, int $id)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.received_qty' => 'required|integer|min:0',
            'items.*.damaged_qty' => 'nullable|integer|min:0',
        ]);

        try {
            $this->transferService->receiveTransfer(
                $this->businessId,
                $id,
                $validated['items']
            );

            return redirect()->route('stock.transfers')
                ->with('success', 'Transfer received successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
