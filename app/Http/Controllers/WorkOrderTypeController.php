<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\WorkOrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkOrderTypeController extends Controller
{
    protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business_id ?? session('business_id');
            if (!$this->businessId) {
                abort(403, 'No business selected.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of work order types.
     */
    public function index()
    {
        $types = WorkOrderType::forBusiness($this->businessId)
            ->orderBy('sort_order')
            ->get();

        return view('work-order-types.index', compact('types'));
    }

    /**
     * Show the form for creating a new work order type.
     */
    public function create()
    {
        $business = Business::find($this->businessId);
        return view('work-order-types.create', compact('business'));
    }

    /**
     * Store a newly created work order type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:work_order_types,slug,NULL,id,business_id,' . $this->businessId,
            'description' => 'nullable|string',
            'config' => 'required|array',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure slug is unique for this business
        $slug = $validated['slug'];
        $originalSlug = $slug;
        $counter = 1;
        while (WorkOrderType::forBusiness($this->businessId)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Get max sort order
        $maxSortOrder = WorkOrderType::forBusiness($this->businessId)->max('sort_order') ?? 0;

        WorkOrderType::create([
            'business_id' => $this->businessId,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'config' => $validated['config'],
            'sort_order' => $maxSortOrder + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('work-order-types.index')
            ->with('success', 'Work order type created successfully!');
    }

    /**
     * Show the form for editing a work order type.
     */
    public function edit($id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);
        $business = Business::find($this->businessId);

        return view('work-order-types.edit', compact('type', 'business'));
    }

    /**
     * Update the specified work order type.
     */
    public function update(Request $request, $id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:work_order_types,slug,' . $id . ',id,business_id,' . $this->businessId,
            'description' => 'nullable|string',
            'config' => 'required|array',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure slug is unique for this business
        $slug = $validated['slug'];
        $originalSlug = $slug;
        $counter = 1;
        while (WorkOrderType::forBusiness($this->businessId)
            ->where('slug', $slug)
            ->where('id', '!=', $id)
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $type->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'config' => $validated['config'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('work-order-types.index')
            ->with('success', 'Work order type updated successfully!');
    }

    /**
     * Remove the specified work order type.
     */
    public function destroy($id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);

        // Check if any work orders are using this type
        if ($type->workOrders()->exists()) {
            return redirect()
                ->route('work-order-types.index')
                ->with('error', 'Cannot delete this type because it has associated work orders.');
        }

        $type->delete();

        return redirect()
            ->route('work-order-types.index')
            ->with('success', 'Work order type deleted successfully!');
    }

    /**
     * Toggle status (activate/deactivate).
     */
    public function toggleStatus($id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);
        $type->update(['is_active' => !$type->is_active]);

        $status = $type->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('work-order-types.index')
            ->with('success', "Work order type {$status} successfully!");
    }

    /**
     * Reorder work order types.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:work_order_types,id',
        ]);

        foreach ($request->order as $index => $id) {
            WorkOrderType::forBusiness($this->businessId)
                ->where('id', $id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get work order type details (for API).
     */
    public function show($id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);
        return response()->json($type);
    }

    /**
     * Get all active work order types (for API).
     */
    public function getActiveTypes()
    {
        $types = WorkOrderType::forBusiness($this->businessId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'description', 'config']);

        return response()->json($types);
    }

    /**
     * Duplicate a work order type.
     */
    public function duplicate($id)
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($id);

        $newType = $type->replicate();
        $newType->name = $type->name . ' (Copy)';
        $newType->slug = $type->slug . '-copy';
        $newType->sort_order = WorkOrderType::forBusiness($this->businessId)->max('sort_order') + 1;
        $newType->is_active = false;
        $newType->save();

        return redirect()
            ->route('work-order-types.index')
            ->with('success', 'Work order type duplicated successfully!');
    }
}