<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * One workbook, two sheets:
 *   1) Purchases         - wheat purchase ledger (no paid / due columns)
 *   2) Supplier Payments - supplier-wise purchases, payments, outstanding + grand total
 */
class WheatPurchaseExport implements WithMultipleSheets
{
    protected Builder $query;
    protected float $totalPurchase;
    protected $supplierRows;

    public function __construct(Builder $query, float $totalPurchase, $supplierRows)
    {
        $this->query         = clone $query;
        $this->totalPurchase = $totalPurchase;
        $this->supplierRows  = $supplierRows;
    }

    public function sheets(): array
    {
        return [
            new WheatPurchaseSheet($this->query, $this->totalPurchase),
            new SupplierPaymentExport($this->supplierRows),
        ];
    }
}