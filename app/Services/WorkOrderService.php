<?php
// app/Services/WorkOrderService.php

namespace App\Services;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;

class WorkOrderService
{
    public function generateNumber(int $businessId, string $typeSlug): string
    {
        $prefix = strtoupper(substr($typeSlug, 0, 3)) . '-' . now()->format('Y');
        $last = WorkOrder::forBusiness($businessId)
            ->where('work_order_no', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->max('work_order_no');

        $num = $last ? (int) substr($last, -5) + 1 : 1;
        return $prefix . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function start(WorkOrder $wo): WorkOrder
    {
        abort_if(!$wo->canStart(), 403);
        $wo->update(['status' => WorkOrder::STATUS_IN_PROGRESS, 'started_at' => now(), 'progress' => max($wo->progress, 1)]);
        return $wo;
    }

    public function hold(WorkOrder $wo, ?string $reason = null): WorkOrder
    {
        abort_if($wo->status !== WorkOrder::STATUS_IN_PROGRESS, 403);
        $wo->update([
            'status' => WorkOrder::STATUS_ON_HOLD,
            'internal_notes' => $wo->internal_notes . "\n[ON HOLD] " . now()->format('Y-m-d H:i') . ': ' . ($reason ?: 'No reason'),
        ]);
        return $wo;
    }

    public function resume(WorkOrder $wo): WorkOrder
    {
        abort_if($wo->status !== WorkOrder::STATUS_ON_HOLD, 403);
        $wo->update(['status' => WorkOrder::STATUS_IN_PROGRESS]);
        return $wo;
    }

    public function updateProgress(WorkOrder $wo, float $progress, ?float $actualHours = null, ?float $actualCost = null): WorkOrder
    {
        abort_if(!in_array($wo->status, [WorkOrder::STATUS_IN_PROGRESS, WorkOrder::STATUS_ON_HOLD]), 403);

        $data = ['progress' => min(100, max(0, $progress))];
        if ($actualHours !== null) $data['actual_hours'] = $actualHours;
        if ($actualCost !== null) $data['actual_cost'] = $actualCost;

        if ($data['progress'] >= 100) {
            $data['status'] = WorkOrder::STATUS_COMPLETED;
            $data['completed_at'] = now();
        }

        $wo->update($data);
        return $wo;
    }

    public function complete(WorkOrder $wo, ?string $notes = null): WorkOrder
    {
        abort_if(!$wo->canComplete(), 403);
        $wo->update([
            'status'           => WorkOrder::STATUS_COMPLETED,
            'progress'         => 100,
            'completed_at'     => now(),
            'completion_notes' => $notes,
        ]);
        return $wo;
    }

    public function cancel(WorkOrder $wo, ?string $reason = null): WorkOrder
    {
        abort_if(!$wo->canCancel(), 403);
        $wo->update([
            'status'       => WorkOrder::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'progress'     => 0,
            'internal_notes' => $wo->internal_notes . "\n[CANCELLED] " . now()->format('Y-m-d H:i') . ': ' . ($reason ?: 'No reason'),
        ]);
        return $wo;
    }

    public function close(WorkOrder $wo): WorkOrder
    {
        abort_if($wo->status !== WorkOrder::STATUS_COMPLETED, 403, 'Only completed work orders can be closed.');
        $wo->update(['status' => WorkOrder::STATUS_CLOSED]);
        return $wo;
    }

    public function reopen(WorkOrder $wo): WorkOrder
    {
        abort_if(!$wo->canReopen(), 403);
        $wo->update([
            'status'       => WorkOrder::STATUS_PENDING,
            'completed_at' => null,
            'cancelled_at' => null,
            'progress'     => 0,
        ]);
        return $wo;
    }
}