<?php

namespace App\Services;

use App\Models\Stock\StockBalance;
use App\Models\Stock\StockReservation;
use Illuminate\Support\Facades\DB;

class StockReservationService
{
    public function getReservations(int $sellerId, array $filters = [])
    {
        $query = StockReservation::with(['product', 'warehouse'])
            ->where('business_id', $sellerId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($product) use ($search) {
                        $product->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_code', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function createReservation(
        int $sellerId,
        array $data
    ): StockReservation {
        return DB::transaction(function () use ($sellerId, $data) {
            $balance = StockBalance::where('business_id', $sellerId)
                ->where('product_id', $data['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (!$balance) {
                throw new \Exception(
                    'No stock balance found for this product and warehouse.'
                );
            }

            if ($balance->available_qty < $data['qty']) {
                throw new \Exception('Insufficient available stock.');
            }

            $balance->reserved_qty += (int) $data['qty'];
            $balance->save();

            return StockReservation::create([
                'product_id'    => $data['product_id'],
                'warehouse_id'  => $data['warehouse_id'],
                'business_id'     => $sellerId,
                'qty'           => $data['qty'],
                'order_id'      => $data['order_id'] ?? null,
                'order_number'  => $data['order_number'] ?? null,
                'customer_name' => $data['customer_name'] ?? null,
                'status'        => 'active',
                'expires_at'    => $data['expires_at'] ?? now()->addHours(24),
            ]);
        });
    }

    public function fulfill(int $sellerId, int $reservationId): void
    {
        DB::transaction(function () use ($sellerId, $reservationId) {
            $reservation = StockReservation::where('business_id', $sellerId)
                ->lockForUpdate()
                ->findOrFail($reservationId);

            if ($reservation->status !== 'active') {
                throw new \Exception('Reservation is not active.');
            }

            $balance = StockBalance::where('business_id', $sellerId)
                ->where('product_id', $reservation->product_id)
                ->where('warehouse_id', $reservation->warehouse_id)
                ->lockForUpdate()
                ->firstOrFail();

            $balance->reserved_qty = max(0, $balance->reserved_qty - $reservation->qty);
            $balance->total_qty    = max(0, $balance->total_qty - $reservation->qty);
            $balance->last_moved_at = now();
            $balance->save();

            $reservation->fulfill();
        });
    }

    public function cancel(
        int $sellerId,
        int $reservationId,
        ?string $reason = null
    ): void {
        DB::transaction(function () use ($sellerId, $reservationId, $reason) {
            $reservation = StockReservation::where('business_id', $sellerId)
                ->lockForUpdate()
                ->findOrFail($reservationId);

            if ($reservation->status !== 'active') {
                throw new \Exception('Reservation is not active.');
            }

            $balance = StockBalance::where('business_id', $sellerId)
                ->where('product_id', $reservation->product_id)
                ->where('warehouse_id', $reservation->warehouse_id)
                ->lockForUpdate()
                ->first();

            if ($balance) {
                $balance->reserved_qty = max(0, $balance->reserved_qty - $reservation->qty);
                $balance->save();
            }

            $reservation->cancel($reason);
        });
    }

    public function expireReservations(int $sellerId): int
    {
        $reservations = StockReservation::where('business_id', $sellerId)
            ->active()
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($reservations as $reservation) {
            $this->cancel($sellerId, $reservation->id, 'Reservation expired');
            $count++;
        }

        return $count;
    }
}
