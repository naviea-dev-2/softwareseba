<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Vendor;
use App\Models\Production\RawMaterial;
use App\Models\Supplier;
use App\Traits\BusinessProductionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialController extends Controller
{
    use BusinessProductionTrait;

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
        $query = $this->businessQuery(RawMaterial::class)
            ->with('supplier');

        $query = $this->applyFilters($query, $request, [
            'search' => [
                'name',
                'sku',
                'description',
            ],

            'status' => true,

            'filters' => [
                'supplier_id' => 'supplier_id',
                'unit_of_measure' => 'unit_of_measure',
            ],
        ]);

        $materials = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'production.materials.index',
            compact('materials')
        );
    }

    public function create()
    {
        $suppliers = Vendor::latest()->get();

        return view(
            'production.materials.create',
            compact('suppliers')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'sku' => 'nullable',
            'description' => 'nullable',
            'unit_of_measure' => 'required',
            'supplier_id' => 'nullable',
            'cost_per_unit' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        RawMaterial::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.materials.index')
            ->with('success', 'Raw material created successfully.');
    }

    public function show($id)
    {
        $material = $this->findBusinessRecord(
            RawMaterial::class,
            $id
        );

        $material->load('supplier');

        return view(
            'production.materials.show',
            compact('material')
        );
    }

    public function edit($id)
    {
        $material = $this->findBusinessRecord(
            RawMaterial::class,
            $id
        );

        $suppliers = Vendor::latest()->get();

        return view(
            'production.materials.edit',
            compact('material', 'suppliers')
        );
    }

    public function update(Request $request, $id)
    {
        $material = $this->findBusinessRecord(
            RawMaterial::class,
            $id
        );

        $validated = $request->validate([
            'name' => 'required',
            'sku' => 'nullable',
            'description' => 'nullable',
            'unit_of_measure' => 'required',
            'supplier_id' => 'nullable',
            'cost_per_unit' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $material->update($validated);

        return redirect()
            ->route('production.materials.index')
            ->with('success', 'Raw material updated successfully.');
    }

    public function destroy($id)
    {
        $this->deleteBusinessRecord(
            RawMaterial::class,
            $id
        );

        return redirect()
            ->route('production.materials.index')
            ->with('success', 'Raw material deleted successfully.');
    }
}