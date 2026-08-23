<?php

namespace App\Services;

use App\Models\Stock\WarehouseTransfer;
use App\Models\Stock\StockBalance;
use App\Models\Stock\StockMovement;
use App\Models\Stock\WarehouseTransferItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseTransferService
{
    public function getTransfers(int $sellerId, array $filters = [])
    {
        $query = WarehouseTransfer::with([
            'fromWarehouse',
            'toWarehouse',
            'items.product',
            'requester',
            'approver',
            'receiver',
        ])->where('business_id', $sellerId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->whereHas('fromWarehouse', function ($warehouse) use ($search) {
                    $warehouse->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('toWarehouse', function ($warehouse) use ($search) {
                    $warehouse->where('name', 'like', "%{$search}%");
                });
            });
        }

        return $query->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function createTransfer(
        int $sellerId,
        array $data
    ): WarehouseTransfer {
        if ($data['from_warehouse_id'] == $data['to_warehouse_id']) {
            throw new \Exception('Source and destination warehouses must be different.');
        }

        return DB::transaction(function () use ($sellerId, $data) {
            $businessId = Auth::user()->business ? Auth::user()->business->business_type_id : 0;

            foreach ($data['items'] as $item) {
                $balance = StockBalance::where('business_id', $sellerId)
                    ->where('product_id', $item['product_id'])
                    ->where('warehouse_id', $data['from_warehouse_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($balance->available_qty < $item['qty']) {
                    throw new \Exception("Insufficient stock for product {$item['product_id']}");
                }
            }

            $transfer = WarehouseTransfer::create([
                'business_id'         => $sellerId,
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id'   => $data['to_warehouse_id'],
                'status'            => 'pending',
                'requested_by'      => $businessId,
                'notes'             => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                WarehouseTransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'status'      => 'pending',
                ]);
            }

            return $transfer->load([
                'items.product',
                'fromWarehouse',
                'toWarehouse'
            ]);
        });
    }

    public function approveTransfer(int $sellerId, int $transferId): void
    {
        $businessId = Auth::user()->business ? Auth::user()->business->business_type_id : 0;
        $transfer = WarehouseTransfer::where('business_id', $sellerId)
            ->findOrFail($transferId);

        if ($transfer->status !== 'pending') {
            throw new \Exception('Only pending transfers can be approved.');
        }

        $transfer->approve($businessId);
    }

    public function shipTransfer(
        int $sellerId,
        int $transferId,
        array $data
    ): void {
        DB::transaction(function () use ($sellerId, $transferId, $data) {
            $businessId = Auth::user()->business ? Auth::user()->business->business_type_id : 0;

            $transfer = WarehouseTransfer::with(['items', 'toWarehouse', 'fromWarehouse'])
                ->where('business_id', $sellerId)
                ->lockForUpdate()
                ->findOrFail($transferId);

            if ($transfer->status !== 'approved') {
                throw new \Exception('Transfer must be approved before shipping.');
            }

            foreach ($transfer->items as $item) {
                $balance = StockBalance::where('business_id', $sellerId)
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $transfer->from_warehouse_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($balance->available_qty < $item->qty) {
                    throw new \Exception('Insufficient stock while shipping.');
                }

                $beforeQty = $balance->total_qty;
                $afterQty  = $beforeQty - $item->qty;

                StockMovement::create([
                    'product_id'        => $item->product_id,
                    'warehouse_id'      => $transfer->from_warehouse_id,
                    'business_id'         => $sellerId,
                    'type'              => 'transfer_out',
                    'qty'               => -$item->qty,
                    'before_qty'        => $beforeQty,
                    'after_qty'         => $afterQty,
                    'reference_type'    => 'transfer',
                    'reference_id'      => 'TR-' . $transfer->id,
                    'reason'            => 'Transfer to ' . $transfer->toWarehouse->name,
                    'from_warehouse_id' => $transfer->from_warehouse_id,
                    'to_warehouse_id'   => $transfer->to_warehouse_id,
                    'user_id'           => $businessId,
                    'user_name'         => Auth::user()->name ?? 'System',
                ]);

                $balance->total_qty     = $afterQty;
                $balance->last_moved_at = now();
                $balance->save();

                $item->markShipped($item->qty);
            }

            $transfer->ship(
                $data['tracking_number'] ?? null,
                $data['carrier'] ?? null
            );
        });
    }

    public function receiveTransfer(
        int $sellerId,
        int $transferId,
        array $items
    ): void {
        DB::transaction(function () use ($sellerId, $transferId, $items) {
            $businessId = Auth::user()->business ? Auth::user()->business->business_type_id : 0;

            $transfer = WarehouseTransfer::with(['items', 'fromWarehouse', 'toWarehouse'])
                ->where('business_id', $sellerId)
                ->lockForUpdate()
                ->findOrFail($transferId);

            if ($transfer->status !== 'in_transit') {
                throw new \Exception('Transfer must be in transit.');
            }

            foreach ($transfer->items as $item) {
                $receivedQty = (int) ($items[$item->id]['received_qty'] ?? 0);
                $damagedQty  = (int) ($items[$item->id]['damaged_qty'] ?? 0);

                if ($receivedQty < 0 || $damagedQty < 0) {
                    throw new \Exception('Invalid received quantity.');
                }

                if ($receivedQty + $damagedQty > $item->shipped_qty) {
                    throw new \Exception('Received quantity cannot exceed shipped quantity.');
                }

                $balance = StockBalance::firstOrCreate(
                    [
                        'business_id'    => $sellerId,
                        'product_id'   => $item->product_id,
                        'warehouse_id' => $transfer->to_warehouse_id,
                    ],
                    [
                        'total_qty'     => 0,
                        'reserved_qty'  => 0,
                        'available_qty' => 0,
                        'incoming_qty'  => 0,
                    ]
                );

                $beforeQty = $balance->total_qty;
                $afterQty  = $beforeQty + $receivedQty;

                if ($receivedQty > 0) {
                    StockMovement::create([
                        'product_id'        => $item->product_id,
                        'warehouse_id'      => $transfer->to_warehouse_id,
                        'business_id'         => $sellerId,
                        'type'              => 'transfer_in',
                        'qty'               => $receivedQty,
                        'before_qty'        => $beforeQty,
                        'after_qty'         => $afterQty,
                        'reference_type'    => 'transfer',
                        'reference_id'      => 'TR-' . $transfer->id,
                        'reason'            => 'Transfer from ' . $transfer->fromWarehouse->name,
                        'from_warehouse_id' => $transfer->from_warehouse_id,
                        'to_warehouse_id'   => $transfer->to_warehouse_id,
                        'user_id'           => $businessId,
                        'user_name'         => Auth::user()->name ?? 'System',
                    ]);
                }

                $balance->total_qty     = $afterQty;
                $balance->last_moved_at = now();
                $balance->save();

                $item->markReceived($receivedQty, $damagedQty);
            }

            $transfer->receive($businessId);
        });
    }
}