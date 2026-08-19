<?php

namespace App\Services;

use App\Models\DealerDelivery;
use App\Models\DealerDeliveryItem;
use App\Models\DealerDeliveryTracking;
use Illuminate\Support\Facades\DB;

class DealerDeliveryService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $delivery = DealerDelivery::create([
                'dealer_id'        => $data['dealer_id'],
                'purchase_order_id'=> $data['purchase_order_id'] ?? null,
                'delivery_number'      => $this->generateDeliveryNo(),
                'delivery_date'    => $data['delivery_date'],
                'depot_id'         => $data['depot_id'] ?? null,
                'vehicle_no'       => $data['vehicle_no'] ?? null,
                'driver_name'      => $data['driver_name'] ?? null,
                'driver_mobile'    => $data['driver_mobile'] ?? null,
                'status'           => 'pending',
                'note'             => $data['note'] ?? null,
                'created_by'       => auth()->user()->id,
            ]);

            foreach ($data['items'] as $item) {

                DealerDeliveryItem::create([
                    'dealer_delivery_id'   => $delivery->id,
                    'product_id'           => $item['product_id'],
                    'purchase_order_item_id' => $data['purchase_order_id'] ?? null,
                    'quantity'             => $item['quantity'],
                    'unit_price'           => $item['unit_price'] ?? 0,
                    'note'                 => $item['note'] ?? null,
                ]);
            }

            DealerDeliveryTracking::create([
                'dealer_delivery_id' => $delivery->id,
                'status'              => 'pending',
                'location'            => null,
                'remarks'             => 'Delivery created',
                'created_at'          => now(),
                'created_by'          => auth()->user()->id,
            ]);

            return $delivery;
        });
    }

    public function update(DealerDelivery $delivery, array $data)
    {
        return DB::transaction(function () use ($delivery, $data) {

            $delivery->update([
                'dealer_id'         => $data['dealer_id'],
                'purchase_order_id' => $data['purchase_order_id'] ?? null,
                'delivery_date'     => $data['delivery_date'],
                'depot_id'          => $data['depot_id'] ?? null,
                'vehicle_no'        => $data['vehicle_no'] ?? null,
                'driver_name'       => $data['driver_name'] ?? null,
                'driver_mobile'     => $data['driver_mobile'] ?? null,
                'note'              => $data['note'] ?? null,
            ]);

            $delivery->items()->delete();

            foreach ($data['items'] as $item) {

                DealerDeliveryItem::create([
                    'dealer_delivery_id'   => $delivery->id,
                    'product_id'           => $item['product_id'],
                    'purchase_order_item_id' =>
                        $item['purchase_order_item_id'] ?? null,
                    'quantity'             => $item['quantity'],
                    'unit_price'           => $item['unit_price'] ?? 0,
                    'note'                 => $item['note'] ?? null,
                ]);
            }

            return $delivery;
        });
    }

    public function updateStatus(
        DealerDelivery $delivery,
        string $status,
        ?string $location = null,
        ?string $remarks = null
    ) {

        return DB::transaction(function () use (
            $delivery,
            $status,
            $location,
            $remarks
        ) {

            $delivery->update([
                'status' => $status,
            ]);

            DealerDeliveryTracking::create([
                'dealer_delivery_id' => $delivery->id,
                'status'             => $status,
                'location'           => $location,
                'remarks'            => $remarks,
                'created_at'         => now(),
                'created_by'         => auth()->user()->id,
            ]);

            return $delivery;
        });
    }

    private function generateDeliveryNo()
    {
        $prefix = 'DEL-' . date('Ymd');

        $last = DealerDelivery::where(
            'delivery_number',
            'like',
            $prefix . '%'
        )->latest('id')->first();

        if (!$last) {
            return $prefix . '-0001';
        }

        $number = (int) substr($last->delivery_number, -4);

        return $prefix . '-' . str_pad(
            $number + 1,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}