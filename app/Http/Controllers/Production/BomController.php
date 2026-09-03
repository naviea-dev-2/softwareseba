<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\BillOfMaterial;
use App\Models\Inventory\Product;
use App\Models\Production\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
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

    public function index()
    {
        $boms = BillOfMaterial::where(
            'user_id',
            $this->businessId
        )
            ->with('items')
            ->latest()
            ->paginate(20);

        return view(
            'production.boms.index',
            compact('boms')
        );
    }

    public function create()
    {
        $products = Product::latest()->get();

        $rawMaterials = RawMaterial::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.boms.create',
            compact('products', 'rawMaterials')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bom_number' => 'required|unique:bill_of_materials,bom_number',
            'name' => 'required',
            'product_id' => 'nullable',
            'version' => 'nullable',
            'unit_of_measure' => 'nullable',
            'is_default' => 'nullable',
            'items' => 'required|array',
            'items.*.component_name' => 'required',
            'items.*.quantity_required' => 'required|numeric',
            'items.*.raw_material_id' => 'nullable',
            'items.*.unit_of_measure' => 'nullable',
            'items.*.wastage_percentage' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            $bom = BillOfMaterial::create([
                'user_id' => $this->businessId,
                'product_id' => $validated['product_id'] ?? null,
                'bom_number' => $validated['bom_number'],
                'version' => $validated['version'] ?? 1,
                'name' => $validated['name'],
                'unit_of_measure' => $validated['unit_of_measure'] ?? null,
                'is_default' => $validated['is_default'] ?? 0,
            ]);

            foreach ($validated['items'] as $item) {
                $bom->items()->create([
                    'raw_material_id' => $item['raw_material_id'] ?? null,
                    'component_name' => $item['component_name'],
                    'quantity_required' => $item['quantity_required'],
                    'unit_of_measure' => $item['unit_of_measure'] ?? null,
                    'wastage_percentage' => $item['wastage_percentage'] ?? 0,
                ]);
            }
        });

        return redirect()
            ->route('production.boms.index')
            ->with('success', 'BOM created successfully.');
    }

    public function show($id)
    {
        $bom = BillOfMaterial::where(
            'user_id',
            $this->businessId
        )
            ->with('items')
            ->findOrFail($id);

        return view(
            'production.boms.show',
            compact('bom')
        );
    }

    public function edit($id)
    {
        $bom = BillOfMaterial::where(
            'user_id',
            $this->businessId
        )
            ->with('items')
            ->findOrFail($id);

        $products = Product::latest()->get();

        $rawMaterials = RawMaterial::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.boms.edit',
            compact('bom', 'products', 'rawMaterials')
        );
    }

    public function update(Request $request, $id)
    {
        $bom = BillOfMaterial::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'bom_number' => 'required|unique:bill_of_materials,bom_number,' . $id,
            'name' => 'required',
            'product_id' => 'nullable',
            'version' => 'nullable',
            'unit_of_measure' => 'nullable',
            'is_default' => 'nullable',
        ]);

        $bom->update($validated);

        return redirect()
            ->route('production.boms.index')
            ->with('success', 'BOM updated successfully.');
    }

    public function destroy($id)
    {
        BillOfMaterial::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.boms.index')
            ->with('success', 'BOM deleted successfully.');
    }
}