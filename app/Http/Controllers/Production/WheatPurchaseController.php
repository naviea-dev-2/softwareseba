<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\RawMaterial;
use App\Models\Production\WheatPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WheatPurchaseController extends Controller
{
     protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;

            return $next($request);
        });
    }

    private function baseQuery(Request $request)
    {
        return WheatPurchase::query()->where('user_id', $this->businessId)
            ->when($request->filled('from'), fn ($q) => $q->whereDate('purchase_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('purchase_date', '<=', $request->input('to')))
            ->when($request->filled('supplier'), fn ($q) => $q->where('supplier_name', 'like', '%'.$request->input('supplier').'%'))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->input('search');
                $q->where(fn ($q) => $q->where('weight_slip_no', 'like', "%{$s}%")
                    ->orWhere('vehicle_no', 'like', "%{$s}%")
                    ->orWhere('driver_name', 'like', "%{$s}%"));
            });
    }

    private function summary($query): array
    {
        $row = (clone $query)->selectRaw('COUNT(*) as deliveries, COALESCE(SUM(net_weight_kg),0) as total_kg, COALESCE(SUM(purchase_amount),0) as amount')->first();
        return ['deliveries' => (int) $row->deliveries, 'total_kg' => (float) $row->total_kg, 'amount' => (float) $row->amount];
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery($request);
        $summary = $this->summary($query);
        $purchases = (clone $query)->orderByDesc('purchase_date')->orderByDesc('id')->paginate(25)->withQueryString();
        return view('production.wheat-purchases.index', compact('purchases', 'summary'));
    }

    private function materials()
    {
        return RawMaterial::where('user_id', $this->businessId)->orderBy('name')->get(['id','name','unit_of_measure']);
    }

    public function create() { $materials = $this->materials(); return view('production.wheat-purchases.create', compact('materials')); }
    public function edit(int $id) { $purchase = $this->owned($id); $materials = $this->materials(); return view('production.wheat-purchases.edit', compact('purchase','materials')); }
    public function show(int $id) { $purchase = $this->owned($id); return view('production.wheat-purchases.show', compact('purchase')); }
    private function owned(int $id): WheatPurchase { return WheatPurchase::where('user_id', $this->businessId)->findOrFail($id); }

    private function validated(Request $request, ?int $id = null): array
    {
        $scope = $this->businessId;
        $data = $request->validate([
            'purchase_date' => 'nullable|date',
            'weight_slip_no' => ['required','string','max:100', Rule::unique('production_wheat_purchases')->where('user_id', $scope)->ignore($id)],
            'raw_material_id' => ['nullable','integer', Rule::exists('production_raw_materials', 'id')->where('user_id', $scope)],
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
        $data['user_id'] = $this->businessId;
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
    public function report(Request $request)
    {
        $query = $this->baseQuery($request);
        $summary = $this->summary($query);
        $purchases = (clone $query)->orderBy('purchase_date')->orderBy('id')->get();
        $monthly = (clone $query)->whereNotNull('purchase_date')->selectRaw("DATE_FORMAT(purchase_date, '%Y-%m') as period, COUNT(*) as deliveries, SUM(net_weight_kg) as total_kg, SUM(purchase_amount) as amount")
            ->groupBy('period')->orderBy('period')->get();
        $suppliers = (clone $query)->selectRaw('supplier_name, COUNT(*) as deliveries, SUM(net_weight_kg) as total_kg, SUM(purchase_amount) as amount')
            ->groupBy('supplier_name')->orderByDesc('total_kg')->get();
        return view('production.wheat-purchases.report', compact('purchases','summary','monthly','suppliers'));
    }
    public function export(Request $request)
    {
        $purchases = $this->baseQuery($request)->orderBy('purchase_date')->orderBy('id')->cursor();
        return response()->streamDownload(function () use ($purchases) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['SI NO','Date','Weight ID','Vehicle No','Driver Name',"Driver's Mobile",'Quantity','Unload Weight (kg)','Load Weight (kg)','Net Weight (Kg)','Party Name','Slip Receiver','Monthly Purchase TON','Per (Price kg)','Total Purchase (Price kg)']);
            $n = 0;
            foreach ($purchases as $p) {
                fputcsv($out, [++$n, optional($p->purchase_date)->format('Y-m-d'), $p->weight_slip_no, $p->vehicle_no, $p->driver_name, $p->driver_mobile,
                    $p->quantity_description ?: ($p->bag_quantity === null ? '' : $p->bag_quantity.' Bag'), $p->empty_weight_kg, $p->loaded_weight_kg, $p->net_weight_kg,
                    $p->supplier_name, $p->slip_receiver, '', $p->rate_per_kg, $p->purchase_amount]);
            }
            fclose($out);
        }, 'wheat-purchase-report-'.now()->format('Ymd-His').'.csv', ['Content-Type'=>'text/csv; charset=UTF-8']);
    }
}
