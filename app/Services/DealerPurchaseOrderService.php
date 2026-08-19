<?php

namespace App\Services;

use App\Models\DealerPurchaseOrder;
use App\Models\DealerPurchaseOrderHistory;
use Illuminate\Support\Facades\DB;

class DealerPurchaseOrderService
{
    public function create(array $data, int $userId): DealerPurchaseOrder
    {
        return DB::transaction(function () use ($data, $userId) {

            $items = $data['items'];

            $totals = $this->calculateTotals($items);

            $po = DealerPurchaseOrder::create([
                'po_number' => $this->generatePoNumber(),

                'dealer_id' => $data['dealer_id'],

                'depot_id' => $data['depot_id'],

                'po_date' => $data['po_date'],

                'expected_delivery_date' =>
                    $data['expected_delivery_date'] ?? null,

                'delivery_address' =>
                    $data['delivery_address'] ?? null,

                'subtotal' => $totals['subtotal'],

                'tax_amount' => $totals['tax_amount'],

                'discount_amount' =>
                    $totals['discount_amount'],

                'grand_total' =>
                    $totals['grand_total'],

                'status' => 'draft',

                'note' => $data['note'] ?? null,

                'created_by' => $userId,
            ]);

            $this->createItems($po, $items);

            $this->addHistory(
                $po,
                'draft',
                'Purchase order created.',
                $userId
            );

            return $po;
        });
    }


    public function update(
        DealerPurchaseOrder $po,
        array $data,
        int $userId
    ): DealerPurchaseOrder {

        return DB::transaction(function () use (
            $po,
            $data,
            $userId
        ) {

            if ($po->status !== 'draft') {

                throw new \Exception(
                    'Only draft purchase orders can be edited.'
                );
            }

            $items = $data['items'];

            $totals = $this->calculateTotals($items);

            $po->update([

                'dealer_id' => $data['dealer_id'],

                'depot_id' => $data['depot_id'],

                'po_date' => $data['po_date'],

                'expected_delivery_date' =>
                    $data['expected_delivery_date'] ?? null,

                'delivery_address' =>
                    $data['delivery_address'] ?? null,

                'subtotal' => $totals['subtotal'],

                'tax_amount' => $totals['tax_amount'],

                'discount_amount' =>
                    $totals['discount_amount'],

                'grand_total' =>
                    $totals['grand_total'],

                'note' => $data['note'] ?? null,
            ]);

            $po->items()->delete();

            $this->createItems($po, $items);

            return $po->fresh();
        });
    }


    public function submit(
        DealerPurchaseOrder $po,
        int $userId
    ): DealerPurchaseOrder {

        return DB::transaction(function () use (
            $po,
            $userId
        ) {

            if ($po->status !== 'draft') {

                throw new \Exception(
                    'Only draft PO can be submitted.'
                );
            }

            $po->update([
                'status' => 'pending_approval',
            ]);

            $this->addHistory(
                $po,
                'pending_approval',
                'Purchase order submitted for approval.',
                $userId
            );

            return $po->fresh();
        });
    }


    public function approve(
        DealerPurchaseOrder $po,
        int $userId
    ): DealerPurchaseOrder {

        return DB::transaction(function () use (
            $po,
            $userId
        ) {

            if ($po->status !== 'pending_approval') {

                throw new \Exception(
                    'Only pending approval PO can be approved.'
                );
            }

            $po->update([

                'status' => 'approved',

                'approved_by' => $userId,

                'approved_at' => now(),
            ]);

            $this->addHistory(
                $po,
                'approved',
                'Purchase order approved.',
                $userId
            );

            return $po->fresh();
        });
    }


    public function reject(
        DealerPurchaseOrder $po,
        int $userId,
        ?string $note = null
    ): DealerPurchaseOrder {

        return DB::transaction(function () use (
            $po,
            $userId,
            $note
        ) {

            if ($po->status !== 'pending_approval') {

                throw new \Exception(
                    'Only pending approval PO can be rejected.'
                );
            }

            $po->update([
                'status' => 'rejected',
            ]);

            $this->addHistory(
                $po,
                'rejected',
                $note ?: 'Purchase order rejected.',
                $userId
            );

            return $po->fresh();
        });
    }


    public function cancel(
        DealerPurchaseOrder $po,
        int $userId
    ): DealerPurchaseOrder {

        return DB::transaction(function () use (
            $po,
            $userId
        ) {

            if (in_array($po->status, [
                'fully_delivered',
                'cancelled',
            ])) {

                throw new \Exception(
                    'This purchase order cannot be cancelled.'
                );
            }

            $po->update([
                'status' => 'cancelled',
            ]);

            $this->addHistory(
                $po,
                'cancelled',
                'Purchase order cancelled.',
                $userId
            );

            return $po->fresh();
        });
    }


    private function calculateTotals(array $items): array
    {
        $subtotal = 0;
        $taxAmount = 0;
        $discountAmount = 0;

        foreach ($items as $item) {

            $quantity =
                (float) $item['quantity'];

            $unitPrice =
                (float) $item['unit_price'];

            $discount =
                (float) ($item['discount_amount'] ?? 0);

            $tax =
                (float) ($item['tax_amount'] ?? 0);

            $gross =
                $quantity * $unitPrice;

            $lineSubtotal =
                max(0, $gross - $discount);

            $lineTotal =
                $lineSubtotal + $tax;

            $subtotal += $gross;

            $discountAmount += $discount;

            $taxAmount += $tax;
        }

        $grandTotal =
            $subtotal
            - $discountAmount
            + $taxAmount;

        return [

            'subtotal' => round($subtotal, 2),

            'tax_amount' => round($taxAmount, 2),

            'discount_amount' =>
                round($discountAmount, 2),

            'grand_total' =>
                round($grandTotal, 2),
        ];
    }


    private function createItems(
        DealerPurchaseOrder $po,
        array $items
    ): void {

        foreach ($items as $item) {

            $quantity =
                (float) $item['quantity'];

            $unitPrice =
                (float) $item['unit_price'];

            $discount =
                (float) ($item['discount_amount'] ?? 0);

            $tax =
                (float) ($item['tax_amount'] ?? 0);

            $gross =
                $quantity * $unitPrice;

            $total =
                max(0, $gross - $discount) + $tax;

            $po->items()->create([

                'product_id' =>
                    $item['product_id'],

                'quantity' =>
                    $quantity,

                'unit' =>
                    $item['unit'] ?? null,

                'unit_price' =>
                    $unitPrice,

                'discount_amount' =>
                    $discount,

                'tax_amount' =>
                    $tax,

                'total_amount' =>
                    round($total, 2),
            ]);
        }
    }


    private function addHistory(
        DealerPurchaseOrder $po,
        string $status,
        string $note,
        int $userId
    ): void {

        DealerPurchaseOrderHistory::create([

            'dealer_purchase_order_id' =>
                $po->id,

            'status' =>
                $status,

            'note' =>
                $note,

            'created_by' =>
                $userId,
        ]);
    }


    private function generatePoNumber(): string
    {
        $prefix =
            'DPO-' . now()->format('Ym') . '-';

        $lastPo = DealerPurchaseOrder::where(
            'po_number',
            'like',
            $prefix . '%'
        )
        ->orderByDesc('id')
        ->first();

        if (!$lastPo) {
            $number = 1;
        } else {

            $lastNumber =
                (int) substr(
                    $lastPo->po_number,
                    strlen($prefix)
                );

            $number =
                $lastNumber + 1;
        }

        return $prefix .
            str_pad(
                $number,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}