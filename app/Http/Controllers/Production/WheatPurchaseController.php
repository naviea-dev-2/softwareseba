<?php

namespace App\Http\Controllers\Production;

use App\Exports\SupplierPaymentExport;
use App\Exports\WheatPurchaseExport;
use App\Http\Controllers\Controller;
use App\Models\Account\BalanceAccount;
use App\Models\Inventory\Vendor;
use App\Models\Production\RawMaterial;
use App\Models\Production\WheatPurchase;
use App\Models\Production\WheatPurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

/**
 * PAID / DUE RULE
 * ---------------
 * production_wheat_purchases.paid_amount is NOT used anywhere.
 *   total purchases = SUM(purchase_amount)  from production_wheat_purchases  (not cancelled, not deleted)
 *   total paid      = SUM(amount)           from production_wheat_purchase_payments (by supplier_id)
 *   due             = total purchases - total paid
 */
class WheatPurchaseController extends Controller
{
    protected $businessId;
    protected $businessTypeId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business
                ? Auth::user()->business->id
                : 0;

            $this->businessTypeId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;

            return $next($request);
        });
    }

    /* =====================================================================
     |  Shared helpers
     * ===================================================================== */

    private function baseQuery(Request $request)
    {
        return WheatPurchase::query()->where('user_id', $this->businessTypeId)
            ->when($request->filled('from'), fn($q) => $q->whereDate('purchase_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->whereDate('purchase_date', '<=', $request->input('to')))
            ->when(
                $request->filled('supplier'),
                fn($q) => $q->where('supplier_id', $request->input('supplier'))
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->input('search');
                $q->where(fn($q) => $q->where('weight_slip_no', 'like', "%{$s}%")
                    ->orWhere('vehicle_no', 'like', "%{$s}%")
                    ->orWhere('driver_name', 'like', "%{$s}%"));
            });
    }

    /**
     * Totals for the filtered purchase list.
     * Purchases come from the purchases table; PAID comes from the payments table,
     * using the same supplier / date filters (date = payment date).
     */
    private function summary(Request $request, $query): array
    {
        $row = (clone $query)
            ->selectRaw('COUNT(*) as deliveries,
                     COALESCE(SUM(net_weight_kg),0) as total_kg,
                     COALESCE(SUM(purchase_amount),0) as amount')
            ->first();

        $paid = (float) WheatPurchasePayment::query()
            ->where('user_id', $this->businessTypeId)
            ->when($request->filled('supplier'), fn($q) => $q->where('supplier_id', $request->input('supplier')))
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->input('to')))
            ->sum('amount');

        $amount = (float) $row->amount;

        return [
            'deliveries' => (int) $row->deliveries,
            'total_kg'   => (float) $row->total_kg,
            'amount'     => $amount,
            'paid'       => $paid,
            'due'        => $amount - $paid,
        ];
    }

    /** All-time balance of ONE supplier: purchases vs payments. */
    private function supplierBalance(int $supplierId): array
    {
        $row = WheatPurchase::query()
            ->where('user_id', $this->businessTypeId)
            ->where('supplier_id', $supplierId)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('COUNT(*) as deliveries,
                         COALESCE(SUM(net_weight_kg),0) as total_kg,
                         COALESCE(SUM(purchase_amount),0) as amount')
            ->first();

        $paid   = (float) WheatPurchasePayment::query()
            ->where('user_id', $this->businessTypeId)
            ->where('supplier_id', $supplierId)
            ->sum('amount');

        $amount = (float) $row->amount;

        return [
            'deliveries' => (int) $row->deliveries,
            'total_kg'   => (float) $row->total_kg,
            'amount'     => $amount,
            'paid'       => $paid,
            'due'        => round($amount - $paid, 2),
        ];
    }

    private function materials()
    {
        return RawMaterial::where('user_id', $this->businessId)->orderBy('name')->get(['id', 'name', 'unit_of_measure']);
    }

    private function bankAccounts()
    {
        return BalanceAccount::where('business_id', $this->businessId)
            ->orderBy('account_name')
            ->get(['id', 'account_name', 'account_number']);
    }

    /* ---------- Vendor (supplier) helpers ----------
     | ADJUST ONLY HERE if your vendors table is scoped differently
     | (e.g. 'user_id' instead of 'business_id') or the name column differs.
     */
    private function vendorQuery()
    {
        return Vendor::query()->where('business_id', $this->businessId);
    }

    private function vendorTable(): string
    {
        return (new Vendor)->getTable();
    }

    private function supplierList()
    {
        return $this->vendorQuery()->orderBy('name')->get(['id', 'name']);
    }

    private function findSupplier(int $id)
    {
        return $this->vendorQuery()->findOrFail($id);
    }

    private function owned(int $id): WheatPurchase
    {
        return WheatPurchase::where('user_id', $this->businessTypeId)->findOrFail($id);
    }

    /* =====================================================================
     |  CRUD
     * ===================================================================== */

    public function index(Request $request)
    {
        $query     = $this->baseQuery($request);
        $summary   = $this->summary($request, $query);
        $purchases = (clone $query)
            ->with('supplier')
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $suppliers = $this->supplierList();

        $totalPurchase = $summary['amount'];
        $totalPaid     = $summary['paid'];
        $totalDue      = $summary['due'];

        return view('production.wheat-purchases.index', compact(
            'purchases',
            'summary',
            'suppliers',
            'totalPurchase',
            'totalPaid',
            'totalDue'
        ));
    }

    public function create()
    {
        $materials = $this->materials();
        $suppliers = $this->supplierList();
        return view('production.wheat-purchases.create', compact('materials', 'suppliers'));
    }

    public function edit(int $id)
    {
        $purchase  = $this->owned($id);
        $materials = $this->materials();
        $suppliers = $this->supplierList();
        return view('production.wheat-purchases.edit', compact('purchase', 'materials', 'suppliers'));
    }

    public function show(int $id)
    {
        $purchase = $this->owned($id)->load('supplier');
        return view('production.wheat-purchases.show', compact('purchase'));
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $scope = $this->businessTypeId;
        $data = $request->validate([
            'purchase_date'        => 'nullable|date',
            'weight_slip_no'       => ['required', 'string', 'max:100', Rule::unique('production_wheat_purchases')->where('user_id', $scope)->ignore($id)],
            'raw_material_id'      => ['nullable', 'integer', Rule::exists('production_raw_materials', 'id')->where('user_id', $scope)],
            'vehicle_no'           => 'nullable|string|max:100',
            'driver_name'          => 'nullable|string|max:150',
            'driver_mobile'        => 'nullable|string|max:30',
            'bag_quantity'         => 'nullable|integer|min:0',
            'quantity_description' => 'nullable|string|max:80',
            'empty_weight_kg'      => 'required|numeric|min:0',
            'loaded_weight_kg'     => 'required|numeric|gt:empty_weight_kg',
            'supplier_id'          => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->vendorQuery()->whereKey($value)->exists()) {
                        $fail('The selected supplier is invalid.');
                    }
                },
            ],
            'slip_receiver'        => 'nullable|string|max:150',
            'rate_per_kg'          => 'required|numeric|min:0',
            'remarks'              => 'nullable|string',
            'status'               => 'required|in:draft,confirmed,cancelled',
        ]);

        $net = (float) $data['loaded_weight_kg'] - (float) $data['empty_weight_kg'];

        $data['net_weight_kg']   = number_format($net, 2, '.', '');
        $data['purchase_amount'] = number_format($net * (float) $data['rate_per_kg'], 2, '.', '');

        // Keep the legacy supplier_name column in sync with the selected vendor.
        $data['supplier_name'] = !empty($data['supplier_id'])
            ? $this->vendorQuery()->whereKey($data['supplier_id'])->value('name')
            : null;

        return $data;
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = $this->businessTypeId;
        WheatPurchase::create($data);

        return redirect()->route('production.wheat-purchases.index')->with('success', 'Wheat delivery saved.');
    }

    public function update(Request $request, int $id)
    {
        $purchase = $this->owned($id);
        $purchase->update($this->validated($request, $id));

        return redirect()->route('production.wheat-purchases.index')->with('success', 'Wheat delivery updated.');
    }

    public function destroy(int $id)
    {
        $this->owned($id)->delete();

        return redirect()->route('production.wheat-purchases.index')->with('success', 'Wheat delivery deleted.');
    }

    /* =====================================================================
     |  SUPPLIER-WISE PAYMENTS
     |  (the old per-purchase pay() is removed: payments are supplier-level)
     * ===================================================================== */

    /**
     * Supplier-wise balance query: purchases (purchases table) vs payments (payments table).
     * Shared by the supplier list page and both Excel exports.
     */
    private function supplierBalanceQuery(?string $search = null, $supplierId = null)
    {
        $vendorTable  = $this->vendorTable();
        $paymentTable = (new WheatPurchasePayment)->getTable();

        // purchases per supplier (from purchases table)
        $purchaseAgg = DB::table('production_wheat_purchases')
            ->where('user_id', $this->businessTypeId)
            ->where('status', '!=', 'cancelled')
            ->whereNull('deleted_at')
            ->whereNotNull('supplier_id')
            ->selectRaw('supplier_id, SUM(purchase_amount) as total_purchases')
            ->groupBy('supplier_id');

        // payments per supplier (from payments table)
        $paymentAgg = DB::table($paymentTable)
            ->where('user_id', $this->businessTypeId)
            ->whereNotNull('supplier_id')
            ->selectRaw('supplier_id, SUM(amount) as total_payments')
            ->groupBy('supplier_id');

        return DB::table("{$vendorTable} as s")
            ->joinSub($purchaseAgg, 'pa', 'pa.supplier_id', '=', 's.id')
            ->leftJoinSub($paymentAgg, 'pp', 'pp.supplier_id', '=', 's.id')
            ->when(filled($search), fn($q) => $q->where('s.name', 'like', '%' . $search . '%'))
            ->when(filled($supplierId), fn($q) => $q->where('s.id', $supplierId))
            ->selectRaw('
                s.id as supplier_id,
                s.name as supplier_name,
                pa.total_purchases,
                COALESCE(pp.total_payments, 0) as total_payments,
                (pa.total_purchases - COALESCE(pp.total_payments, 0)) as outstanding
            ')
            ->orderBy('s.name');
    }

    /**
     * Supplier list: total purchases / total payments / outstanding.
     * View: production.wheat-purchases.supplier-payments.index
     */
    public function supplierPayments(Request $request)
    {
        $suppliers = $this->supplierBalanceQuery($request->input('search'))
            ->paginate(15)
            ->withQueryString();

        return view('production.wheat-purchases.supplier-payments.index', compact('suppliers'));
    }

    /**
     * Separate Excel export: supplier-wise payments with grand total.
     * Respects the search box on the supplier list.
     */
    public function supplierPaymentsExport(Request $request)
    {
        $rows = $this->supplierBalanceQuery($request->input('search'))->get();

        return Excel::download(
            new SupplierPaymentExport($rows),
            'supplier-wise-payments-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    /**
     * One supplier: purchases, payment history, balance and pay form.
     * View: production.wheat-purchases.supplier-payments.show
     */
    public function supplierPaymentsShow(Request $request, int $supplierId)
    {
        $supplier = $this->findSupplier($supplierId);

        // Cards are the supplier's ALL-TIME balance (payments table based).
        $summary = $this->supplierBalance($supplier->id);

        // Date filter only narrows the purchases list.
        $purchases = WheatPurchase::query()
            ->where('user_id', $this->businessTypeId)
            ->where('supplier_id', $supplier->id)
            ->where('status', '!=', 'cancelled')
            ->when($request->filled('from'), fn($q) => $q->whereDate('purchase_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->whereDate('purchase_date', '<=', $request->input('to')))
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $payments = WheatPurchasePayment::query()
            ->where('user_id', $this->businessTypeId)
            ->where('supplier_id', $supplier->id)
            ->latest()
            ->limit(50)
            ->get();

        $bankAccounts = $this->bankAccounts();

        return view('production.wheat-purchases.supplier-payments.show', compact(
            'supplier',
            'summary',
            'purchases',
            'payments',
            'bankAccounts'
        ));
    }

    /**
     * Pay a supplier: ONE row in production_wheat_purchase_payments.
     * Nothing is written to production_wheat_purchases.
     */
    public function supplierPaymentsPay(Request $request, int $supplierId)
    {
        $supplier = $this->findSupplier($supplierId);

        $validated = $request->validate([
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'method'          => ['required', 'in:cash,bkash,bank'],
            'bank_account_id' => [
                'nullable',
                'required_if:method,bank',
                Rule::exists('balance_accounts', 'id')->where('business_id', $this->businessId),
            ],
            'note'            => ['nullable', 'string', 'max:500'],
        ]);

        $amount = round((float) $validated['amount'], 2);

        DB::transaction(function () use ($supplier, $validated, $amount) {
            // Serialize concurrent payments for the same supplier.
            WheatPurchase::query()
                ->where('user_id', $this->businessTypeId)
                ->where('supplier_id', $supplier->id)
                ->lockForUpdate()
                ->pluck('id');

            $balance = $this->supplierBalance($supplier->id);

            if ($balance['due'] <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'This supplier has no outstanding balance.',
                ]);
            }

            if ($amount > $balance['due']) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount cannot exceed the outstanding balance of ' . number_format($balance['due'], 2),
                ]);
            }

            WheatPurchasePayment::create([
                'user_id'         => $this->businessTypeId,
                'supplier_id'     => $supplier->id,
                'amount'          => $amount,
                'method'          => $validated['method'],
                'bank_account_id' => $validated['method'] === 'bank' ? $validated['bank_account_id'] : null,
                'note'            => $validated['note'] ?? null,
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Payment of ' . number_format($amount, 2) . ' recorded for ' . $supplier->name . '.');
    }

    /* =====================================================================
     |  Report / Export
     * ===================================================================== */

    public function report(Request $request)
    {
        $query     = $this->baseQuery($request);
        $summary   = $this->summary($request, $query);
        $purchases = (clone $query)->with('supplier')->orderBy('purchase_date')->orderBy('id')->get();

        $monthly = (clone $query)
            ->whereNotNull('purchase_date')
            ->selectRaw("DATE_FORMAT(purchase_date, '%Y-%m') as period, COUNT(*) as deliveries, SUM(net_weight_kg) as total_kg, SUM(purchase_amount) as amount")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Per-supplier summary table (kept separate from $suppliers, which feeds the filter dropdown).
        $supplierSummary = (clone $query)
            ->leftJoin($this->vendorTable() . ' as s', 's.id', '=', 'production_wheat_purchases.supplier_id')
            ->selectRaw('production_wheat_purchases.supplier_id,
                         COALESCE(s.name, production_wheat_purchases.supplier_name, "Unassigned") as supplier_name,
                         COUNT(*) as deliveries,
                         SUM(production_wheat_purchases.net_weight_kg) as total_kg,
                         SUM(production_wheat_purchases.purchase_amount) as amount')
            ->groupBy('production_wheat_purchases.supplier_id', 's.name', 'production_wheat_purchases.supplier_name')
            ->orderByDesc('total_kg')
            ->get();

        $suppliers = $this->supplierList();

        return view('production.wheat-purchases.report', compact(
            'purchases',
            'summary',
            'monthly',
            'supplierSummary',
            'suppliers'
        ));
    }

    public function export(Request $request)
    {
        $query         = $this->baseQuery($request);
        $totalPurchase = (float) (clone $query)->sum('purchase_amount');

        // 2nd sheet: supplier-wise payments (respects the supplier filter if one is chosen).
        $supplierRows = $this->supplierBalanceQuery(null, $request->input('supplier'))->get();

        return Excel::download(
            new WheatPurchaseExport($query, $totalPurchase, $supplierRows),
            'wheat-purchase-report-' . now()->format('Ymd-His') . '.xlsx'
        );
    }
}