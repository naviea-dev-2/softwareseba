<?php

namespace App\Services;

use App\Models\Stock\StockBalance;
use App\Models\Stock\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockBalanceService
{
    public function getLedger(int $businessId, array $filters = [])
    {
        $query = StockBalance::with(['product', 'warehouse'])
            ->where('business_id', $businessId);

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'low') {
                $query->whereColumn('available_qty', '<=', 'reorder_point')
                    ->where('available_qty', '>', 0);
            } elseif ($filters['status'] === 'critical') {
                $query->where('available_qty', '<=', 0);
            } elseif ($filters['status'] === 'normal') {
                $query->whereColumn('available_qty', '>', 'reorder_point');
            }
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) ($filters['per_page'] ?? 20), 100);

        return $query->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getProductHistory(
        int $businessId,
        int $productId,
        int $warehouseId,
        int $limit = 50
    ) {
        return StockMovement::with([
            'product',
            'warehouse',
            'user',
            'fromWarehouse',
            'toWarehouse'
        ])
            ->where('business_id', $businessId)
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function adjustStock(int $businessId, array $data): StockBalance
    {
        return DB::transaction(function () use ($businessId, $data) {
            $user = Auth::user();
            $userId = $user?->id ?? 0;
            $userName = $user?->name ?? 'System';

            $balance = StockBalance::where('business_id', $businessId)
                ->where('product_id', $data['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->first();

            if (!$balance) {
                $balance = StockBalance::create([
                    'business_id'  => $businessId,
                    'product_id'   => $data['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'total_qty'    => 0,
                    'reserved_qty' => 0,
                    'reorder_point' => $data['reorder_point'] ?? 0,
                ]);
            }

            $beforeQty = (int) $balance->total_qty;

            $adjustmentQty = $data['type'] === 'add'
                ? abs((int) $data['qty'])
                : -abs((int) $data['qty']);

            $afterQty = $beforeQty + $adjustmentQty;

            if ($afterQty < 0) {
                throw new \Exception('Stock quantity cannot be negative.');
            }

            StockMovement::create([
                'product_id'     => $data['product_id'],
                'warehouse_id'   => $data['warehouse_id'],
                'business_id'    => $businessId,
                'type'           => $adjustmentQty > 0 ? 'in' : 'out',
                'qty'            => abs($adjustmentQty),
                'before_qty'     => $beforeQty,
                'after_qty'      => $afterQty,
                'reference_type' => 'adjustment',
                'reference_id'   => $data['reference_id'] ?? 'ADJ-' . now()->format('Ymd-His'),
                'reason'         => $data['reason'] ?? 'Manual adjustment',
                'user_id'        => $userId,
                'user_name'      => $userName,
            ]);

            $balance->total_qty = $afterQty;
            $balance->last_moved_at = now();
            $balance->save();

            return $balance->fresh(['product', 'warehouse']);
        });
    }

    public function getLowStockItems(int $businessId)
    {
        return StockBalance::with(['product', 'warehouse'])
            ->where('business_id', $businessId)
            ->whereColumn('available_qty', '<=', 'reorder_point')
            ->where('available_qty', '>', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name'        => $item->product->product_name ?? 'Unknown',
                    'sku'                 => $item->product->product_code ?? 'N/A',
                    'warehouse'           => $item->warehouse->name ?? 'Unknown',
                    'available_qty'       => $item->available_qty,
                    'reorder_point'       => $item->reorder_point,
                    'days_until_stockout' => $this->estimateStockoutDays($item),
                ];
            });
    }

    public function getTopMovingProducts(
        int $businessId,
        int $limit = 10
    ) {
        return StockMovement::with('product')
            ->where('business_id', $businessId)
            ->where('type', 'out')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('product_id, SUM(ABS(qty)) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->map(function ($item) use ($businessId) {
                $balance = StockBalance::where('business_id', $businessId)
                    ->where('product_id', $item->product_id)
                    ->sum('total_qty');

                $turnover = $balance > 0
                    ? round($item->total_sold / $balance, 1)
                    : 0;

                return [
                    'product_name'  => $item->product->product_name ?? 'Unknown',
                    'sku'           => $item->product->product_code ?? 'N/A',
                    'total_sold'    => (int) $item->total_sold,
                    'turnover_rate' => $turnover,
                ];
            });
    }

    public function getStockValueByWarehouse(int $businessId)
    {
        return StockBalance::with(['warehouse', 'product'])
            ->where('business_id', $businessId)
            ->get()
            ->groupBy('warehouse_id')
            ->map(function ($items) {
                $warehouse  = $items->first()->warehouse;
                $totalValue = $items->sum(function ($item) {
                    return $item->total_qty * ($item->product->sale_price ?? 0);
                });

                return [
                    'warehouse' => $warehouse->name ?? 'Unknown',
                    'value'     => round($totalValue, 2),
                    'sku_count' => $items->count(),
                ];
            })
            ->values();
    }

    private function estimateStockoutDays(StockBalance $balance): int
    {
        $avgDailyOut = StockMovement::where('product_id', $balance->product_id)
            ->where('warehouse_id', $balance->warehouse_id)
            ->where('business_id', $balance->business_id)
            ->where('type', 'out')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('COALESCE(SUM(ABS(qty)),0) / 30 as avg_daily')
            ->value('avg_daily');

        if (!$avgDailyOut || $avgDailyOut <= 0) {
            return 999;
        }

        return (int) ceil($balance->available_qty / $avgDailyOut);
    }
}
