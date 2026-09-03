<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\RawMaterial;
use App\Models\Production\RawMaterialStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialStockController extends Controller
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

    public function index(Request $request)
    {
        $stocks = RawMaterialStock::with('rawMaterial')
            ->where('user_id', $this->businessId)
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('batch_number', 'LIKE', "%{$search}%")
                        ->orWhere('location', 'LIKE', "%{$search}%")
                        ->orWhere('quantity', 'LIKE', "%{$search}%")
                        ->orWhere('expiry_date', 'LIKE', "%{$search}%")
                        ->orWhereHas('rawMaterial', function ($materialQuery) use ($search) {
                            $materialQuery->where(
                                'name',
                                'LIKE',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'production.material-stocks.index',
            compact('stocks')
        );
    }

    public function create()
    {
        $rawMaterials = RawMaterial::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.material-stocks.create',
            compact('rawMaterials')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required',
            'quantity' => 'required|numeric',
            'batch_number' => 'nullable',
            'location' => 'nullable',
            'expiry_date' => 'nullable|date',
        ]);

        RawMaterialStock::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.material-stocks.index')
            ->with('success', 'Stock added successfully.');
    }

    public function show($id)
    {
        $stock = RawMaterialStock::where(
            'user_id',
            $this->businessId
        )
            ->with('rawMaterial')
            ->findOrFail($id);

        return view(
            'production.material-stocks.show',
            compact('stock')
        );
    }

    public function edit($id)
    {
        $stock = RawMaterialStock::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $rawMaterials = RawMaterial::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.material-stocks.edit',
            compact('stock', 'rawMaterials')
        );
    }

    public function update(Request $request, $id)
    {
        $stock = RawMaterialStock::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'raw_material_id' => 'required',
            'quantity' => 'required|numeric',
            'batch_number' => 'nullable',
            'location' => 'nullable',
            'expiry_date' => 'nullable|date',
        ]);

        $stock->update($validated);

        return redirect()
            ->route('production.material-stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        RawMaterialStock::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.material-stocks.index')
            ->with('success', 'Stock deleted successfully.');
    }
}