<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Account\BalanceAccount;
use App\Models\Production\RawMaterial;
use App\Models\Production\WheatPurchase;
use App\Models\Production\WheatPurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    private function baseQuery(Request $request)
    {
        return WheatPurchase::query()->where('user_id', $this->businessTypeId)
            ->when($request->filled('from'), fn($q) => $q->whereDate('purchase_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->whereDate('purchase_date', '<=', $request->input('to')))
            ->when($request->filled('supplier'), fn($q) => $q->where('supplier_name', 'like', '%' . $request->input('supplier') . '%'))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->input('search');
                $q->where(fn($q) => $q->where('weight_slip_no', 'like', "%{$s}%")
                    ->orWhere('vehicle_no', 'like', "%{$s}%")
                    ->orWhere('driver_name', 'like', "%{$s}%"));
            });
    }

    private function summary($query): array
    {
        $row = (clone $query)
            ->selectRaw('COUNT(*) as deliveries,
                     COALESCE(SUM(net_weight_kg),0) as total_kg,
                     COALESCE(SUM(purchase_amount),0) as amount,
                     COALESCE(SUM(paid_amount),0) as paid')
            ->first();

        $amount = (float) $row->amount;
        $paid   = (float) $row->paid;

        return [
            'deliveries' => (int) $row->deliveries,
            'total_kg'   => (float) $row->total_kg,
            'amount'     => $amount,
            'paid'       => $paid,
            'due'        => $amount - $paid,
        ];
    }

    public function index(Request $request)
    {
        $query     = $this->baseQuery($request);
        $summary   = $this->summary($query);
        $purchases = (clone $query)
            ->with(['payments' => fn($q) => $q->latest()])
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $bankAccounts = $this->bankAccounts();

        // NEW — totals for the currently filtered set (not just the page)
        $totalPurchase = $summary['amount'];
        $totalPaid     = $summary['paid'];
        $totalDue      = $summary['due'];

        return view('production.wheat-purchases.index', compact(
            'purchases',
            'summary',
            'bankAccounts',
            'totalPurchase',
            'totalPaid',
            'totalDue'
        ));
    }

    private function materials()
    {
        return RawMaterial::where('user_id', $this->businessId)->orderBy('name')->get(['id', 'name', 'unit_of_measure']);
    }

    /** NEW — bank accounts dropdown data (scoped to the business) */
    private function bankAccounts()
    {
        return BalanceAccount::where('business_id', $this->businessId)
            ->orderBy('account_name')
            ->get(['id', 'account_name', 'account_number']);
    }

    public function create()
    {
        $materials = $this->materials();
        return view('production.wheat-purchases.create', compact('materials'));
    }
    public function edit(int $id)
    {
        $purchase = $this->owned($id);
        $materials = $this->materials();
        return view('production.wheat-purchases.edit', compact('purchase', 'materials'));
    }
    public function show(int $id)
    {
        $purchase = $this->owned($id);
        return view('production.wheat-purchases.show', compact('purchase'));
    }
    private function owned(int $id): WheatPurchase
    {
        return WheatPurchase::where('user_id', $this->businessId)->findOrFail($id);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $scope = $this->businessId;
        $data = $request->validate([
            'purchase_date' => 'nullable|date',
            'weight_slip_no' => ['required', 'string', 'max:100', Rule::unique('production_wheat_purchases')->where('user_id', $scope)->ignore($id)],
            'raw_material_id' => ['nullable', 'integer', Rule::exists('production_raw_materials', 'id')->where('user_id', $scope)],
            'vehicle_no' => 'nullable|string|max:100',
            'driver_name' => 'nullable|string|max:150',
            'driver_mobile' => 'nullable|string|max:30',
            'bag_quantity' => 'nullable|integer|min:0',
            'quantity_description' => 'nullable|string|max:80',
            'empty_weight_kg' => 'required|numeric|min:0',
            'loaded_weight_kg' => 'required|numeric|gt:empty_weight_kg',
            'supplier_name' => 'nullable|string|max:200',
            'slip_receiver' => 'nullable|string|max:150',
            'rate_per_kg' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'status' => 'required|in:draft,confirmed,cancelled',
        ]);
        $net = round((float)$data['loaded_weight_kg'] - (float)$data['empty_weight_kg'], 3);
        $data['net_weight_kg'] = $net;
        $data['purchase_amount'] = round($net * (float)$data['rate_per_kg'], 2);
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

    /**
     * NEW — record a payment against a wheat purchase.
     */
    public function pay(Request $request, int $id)
    {
        $purchase = $this->owned($id);   // ensures the record belongs to this business

        $validated = $request->validate([
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'method'          => ['required', 'in:cash,bkash,bank'],
            'bank_account_id' => [
                'nullable',
                'required_if:method,bank',
                Rule::exists('bank_accounts', 'id')->where('business_id', $this->businessId),
            ],
            'note'            => ['nullable', 'string', 'max:500'],
        ]);

        $paid = (float) ($purchase->paid_amount ?? 0);
        $due  = (float) $purchase->purchase_amount - $paid;

        if ($validated['amount'] > $due) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount cannot exceed the due amount of ' . number_format($due, 2),
            ]);
        }

        DB::transaction(function () use ($purchase, $validated) {
            WheatPurchasePayment::create([
                'user_id'           => $this->businessTypeId,
                'wheat_purchase_id' => $purchase->id,
                'amount'            => $validated['amount'],
                'method'            => $validated['method'],
                'bank_account_id'   => $validated['method'] === 'bank'
                    ? $validated['bank_account_id']
                    : null,
                'note'              => $validated['note'] ?? null,
            ]);

            $purchase->increment('paid_amount', $validated['amount']);
        });

        return redirect()
            ->route('production.wheat-purchases.index')
            ->with('success', 'Payment of ' . number_format($validated['amount'], 2) . ' recorded successfully.');
    }

    public function report(Request $request)
    {
        $query = $this->baseQuery($request);
        $summary = $this->summary($query);
        $purchases = (clone $query)->orderBy('purchase_date')->orderBy('id')->get();
        $monthly = (clone $query)->whereNotNull('purchase_date')->selectRaw("DATE_FORMAT(purchase_date, '%Y-%m') as period, COUNT(*) as deliveries, SUM(net_weight_kg) as total_kg, SUM(purchase_amount) as amount")
            ->groupBy('period')->orderBy('period')->get();
        $suppliers = (clone $query)->selectRaw('supplier_name, COUNT(*) as deliveries, SUM(net_weight_kg) as total_kg, SUM(purchase_amount) as amount')
            ->groupBy('supplier_name')->orderByDesc('total_kg')->get();
        return view('production.wheat-purchases.report', compact('purchases', 'summary', 'monthly', 'suppliers'));
    }

    public function export(Request $request)
    {
        $purchases = $this->baseQuery($request)->orderBy('purchase_date')->orderBy('id')->cursor();
        return response()->streamDownload(function () use ($purchases) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['SI NO', 'Date', 'Weight ID', 'Vehicle No', 'Driver Name', "Driver's Mobile", 'Quantity', 'Unload Weight (kg)', 'Load Weight (kg)', 'Net Weight (Kg)', 'Party Name', 'Slip Receiver', 'Monthly Purchase TON', 'Per (Price kg)', 'Total Purchase (Price kg)', 'Paid Amount', 'Due Amount']);
            $n = 0;
            foreach ($purchases as $p) {
                $paid = (float) ($p->paid_amount ?? 0);
                $due  = (float) $p->purchase_amount - $paid;
                fputcsv($out, [
                    ++$n,
                    optional($p->purchase_date)->format('Y-m-d'),
                    $p->weight_slip_no,
                    $p->vehicle_no,
                    $p->driver_name,
                    $p->driver_mobile,
                    $p->quantity_description ?: ($p->bag_quantity === null ? '' : $p->bag_quantity . ' Bag'),
                    $p->empty_weight_kg,
                    $p->loaded_weight_kg,
                    $p->net_weight_kg,
                    $p->supplier_name,
                    $p->slip_receiver,
                    '',
                    $p->rate_per_kg,
                    $p->purchase_amount,
                    $paid,
                    $due
                ]);
            }
            fclose($out);
        }, 'wheat-purchase-report-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
