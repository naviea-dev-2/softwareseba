<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderType;
use App\Models\WorkOrderItem;
use App\Models\Inventory\Customer;
use App\Models\Inventory\Vendor;
use App\Models\User;
use App\Models\Stock\Warehouse;
use App\Models\Inventory\Product;
use App\Services\WorkOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkOrderController extends Controller
{
    protected $businessId;

    public function __construct(protected WorkOrderService $service)
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = Auth::user()->business_id ?? 0;
            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        $query = WorkOrder::with(['workOrderType', 'customer', 'vendor', 'assignee', 'warehouse']);

        if ($request->filled('type_id')) {
            $query->where('work_order_type_id', $request->type_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        $types = WorkOrderType::where('is_active', true)->get();

        $stats = [
            'total'       => WorkOrder::forBusiness($this->businessId)->count(),
            'open'        => WorkOrder::forBusiness($this->businessId)->open()->count(),
            'overdue'     => WorkOrder::forBusiness($this->businessId)->overdue()->count(),
            'in_progress' => WorkOrder::forBusiness($this->businessId)->status(WorkOrder::STATUS_IN_PROGRESS)->count(),
            'completed'   => WorkOrder::forBusiness($this->businessId)->status(WorkOrder::STATUS_COMPLETED)->count(),
        ];

        return view('work-orders.index', compact('orders', 'types', 'stats'));
    }

    public function create(Request $request): View
    {
        $business = Auth::user()->business;
        $types = WorkOrderType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $selectedType = null;
        if ($request->filled('type_id')) {
            $selectedType = WorkOrderType::findOrFail($request->type_id);
        }

        return view('work-orders.create', array_merge($this->getFormData(), [
            'types' => $types,
            'business' => $business,
            'selectedType' => $selectedType,
        ]));
    }

    /* public function store(Request $request): RedirectResponse
    {
        $type = WorkOrderType::forBusiness($this->businessId)->findOrFail($request->work_order_type_id);

        $rules = [
            'work_order_type_id' => 'required|exists:work_order_types,id',
            'title'              => 'required|string|max:255',
        ];

        foreach ($type->config['fields'] ?? [] as $field => $cfg) {
            if (($cfg['required'] ?? false) && ($cfg['show'] ?? true)) {
                $rules[$field] = 'required';
            }
        }

        foreach ($type->getCustomSections() as $section) {
            foreach ($section['fields'] ?? [] as $f) {
                if ($f['required'] ?? false) {
                    $rules['meta.' . $f['name']] = 'required';
                }
            }
        }

        if ($type->lineItemsEnabled()) {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.description'] = 'required|string';
            $rules['items.*.quantity'] = 'required|numeric|min:0.01';
        }

        $validated = $request->validate($rules);

        $wo = DB::transaction(function () use ($request, $type) {
            $data = [
                'business_id'         => $this->businessId,
                'work_order_type_id'  => $type->id,
                'work_order_no'       => $this->service->generateNumber($this->businessId, $type->slug),
                'work_order_type'     => $type->slug,
                'title'               => $request->title,
                'description'         => $request->input('description'),
                'customer_id'         => $request->input('customer_id'),
                'vendor_id'           => $request->input('vendor_id'),
                'assigned_to'         => $request->input('assigned_to'),
                'warehouse_id'        => $request->input('warehouse_id'),
                'priority'            => $request->input('priority', 'normal'),
                'status'              => WorkOrder::STATUS_DRAFT,
                'scheduled_at'        => $request->input('scheduled_at'),
                'due_at'              => $request->input('due_at'),
                'estimated_cost'      => $request->input('estimated_cost', 0),
                'estimated_hours'     => $request->input('estimated_hours', 0),
                'instructions'        => $request->input('instructions'),
                'internal_notes'      => $request->input('internal_notes'),
                'meta'                => $request->input('meta', []),
                'created_by'          => Auth::id(),
            ];

            $workOrder = WorkOrder::create($data);

            if ($type->lineItemsEnabled() && $request->has('items')) {
                foreach ($request->input('items') as $item) {
                    WorkOrderItem::create([
                        'work_order_id'       => $workOrder->id,
                        'item_type'           => $item['item_type'] ?? 'product',
                        'product_id'          => $item['product_id'] ?? null,
                        'description'         => $item['description'],
                        'quantity'            => $item['quantity'],
                        'unit_cost'           => $item['unit_cost'] ?? 0,
                        'total_cost'          => ($item['unit_cost'] ?? 0) * $item['quantity'],
                        'source_warehouse_id' => $item['source_warehouse_id'] ?? null,
                        'target_warehouse_id' => $request->input('warehouse_id'),
                    ]);
                }
            }

            if ($request->has('download_pdf')) {
                // Generate PDF and return download
                $pdf = PDF::loadView('work-orders.pdf', ['workOrder' => $workOrder]);
                return $pdf->download('work-order-'.$workOrder->work_order_no.'.pdf');
            }
            
            return redirect()->route('work-orders.show', $wo)->with('success', 'Work Order created.');

            //return $workOrder;
        });

        return redirect()->route('work-orders.show', $wo)->with('success', 'Work Order ' . $wo->work_order_no . ' created.');
    } */


    public function store(Request $request): RedirectResponse
    {
        $type = WorkOrderType::findOrFail($request->work_order_type_id);

        $rules = [
            'work_order_type_id' => 'required|exists:work_order_types,id',
            'title'              => 'required|string|max:255',
        ];

        foreach ($type->config['fields'] ?? [] as $field => $cfg) {
            if (($cfg['required'] ?? false) && ($cfg['show'] ?? true)) {
                $rules[$field] = 'required';
            }
        }

        foreach ($type->getCustomSections() as $section) {
            foreach ($section['fields'] ?? [] as $f) {
                if ($f['required'] ?? false) {
                    $rules['meta.' . $f['name']] = 'required';
                }
            }
        }

        if ($type->lineItemsEnabled()) {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.description'] = 'required|string';
            $rules['items.*.quantity'] = 'required|numeric|min:0.01';
        }

        $validated = $request->validate($rules);

        $wo = DB::transaction(function () use ($request, $type) {
            // Get default business info
            $business = Auth::user()->business;

            // Debug: Check if business is loaded
            if (!$business) {
                throw new \Exception('No business found for the authenticated user.');
            }

            // Build company settings with proper null handling
            $companySettings = [
                'name' => $request->input('company_name') ?: ($business->business_name ?? config('app.name')),
                'tagline' => $request->input('company_tagline') ?: ($business->tagline ?? 'Professional Work Order Management'),
                'address' => $request->input('company_address') ?: ($this->formatAddress($business)),
                'city' => $request->input('company_city') ?: ($business->city->name ?? ''),
                'state' => $request->input('company_state') ?: ($business->state->name ?? ''),
                'zip' => $request->input('company_zip') ?: ($business->zip ?? $business->postal_code ?? ''),
                'country' => $request->input('company_country') ?: ($business->country->name ?? ''),
                'phone' => $request->input('company_phone') ?: ($business->phone_number ?? ''),
                'email' => $request->input('company_email') ?: ($business->email ?? ''),
                'website' => $request->input('company_website') ?: ($business->website ?? ''),
                'tax_number' => $request->input('company_tax_number') ?: null,
                'registration_number' => $request->input('company_registration_number') ?: null,
                'footer_text' => $request->input('company_footer_text') ?: 'Thank you for your business!',
            ];

            // Handle custom logo upload
            if ($request->hasFile('company_logo')) {
                $logo = $request->file('company_logo');
                $logoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $logo->getClientOriginalName());
                $logoPath = public_path('upload/business/' . $logoName);
                $logo->move(public_path('upload/business/'), $logoName);
                $companySettings['logo'] = asset('public/upload/business/' . $logoName);
            } elseif ($business && !empty($business->business_logo)) {
                // Use business logo if no custom logo uploaded
                $companySettings['logo'] = $business->business_logo;
            }

            // Check if ANY custom fields were filled
            $hasCustomFields = $request->filled('company_name') ||
                $request->filled('company_tagline') ||
                $request->filled('company_address') ||
                $request->filled('company_city') ||
                $request->filled('company_state') ||
                $request->filled('company_zip') ||
                $request->filled('company_country') ||
                $request->filled('company_phone') ||
                $request->filled('company_email') ||
                $request->filled('company_website') ||
                $request->filled('company_tax_number') ||
                $request->filled('company_registration_number') ||
                $request->filled('company_footer_text') ||
                $request->hasFile('company_logo');

            // Get meta data as array
            $metaData = $request->input('meta', []);
            if (!is_array($metaData)) {
                $metaData = [];
            }

            // Prepare data array
            $data = [
                'business_id'         => $this->businessId,
                'work_order_type_id'  => $type->id,
                'work_order_no'       => $this->service->generateNumber($this->businessId, $type->slug),
                'work_order_type'     => $type->slug,
                'title'               => $request->title,
                'description'         => $request->input('description'),
                'customer_id'         => $request->input('customer_id'),
                'vendor_id'           => $request->input('vendor_id'),
                'assigned_to'         => $request->input('assigned_to'),
                'warehouse_id'        => $request->input('warehouse_id'),
                'priority'            => $request->input('priority', 'normal'),
                'status'              => WorkOrder::STATUS_DRAFT,
                'scheduled_at'        => $request->input('scheduled_at'),
                'due_at'              => $request->input('due_at'),
                'estimated_cost'      => $request->input('estimated_cost', 0),
                'estimated_hours'     => $request->input('estimated_hours', 0),
                'instructions'        => $request->input('instructions'),
                'internal_notes'      => $request->input('internal_notes'),
                'meta'                => $metaData, // Will be auto-cast to JSON
                'company_settings'    => $hasCustomFields ? $companySettings : null,
                'created_by'          => Auth::id(),
            ];

            // Create work order
            $workOrder = WorkOrder::create($data);

            // Create line items if enabled
            if ($type->lineItemsEnabled() && $request->has('items')) {
                foreach ($request->input('items') as $item) {
                    WorkOrderItem::create([
                        'work_order_id'       => $workOrder->id,
                        'item_type'           => $item['item_type'] ?? 'product',
                        'product_id'          => $item['product_id'] ?? null,
                        'description'         => $item['description'],
                        'quantity'            => $item['quantity'],
                        'unit_cost'           => $item['unit_cost'] ?? 0,
                        'total_cost'          => ($item['unit_cost'] ?? 0) * $item['quantity'],
                        'source_warehouse_id' => $item['source_warehouse_id'] ?? null,
                        'target_warehouse_id' => $request->input('warehouse_id'),
                    ]);
                }
            }

            return $workOrder;
        });

        // Store success data in session
        return redirect()
            ->route('work-orders.create', ['type_id' => $type->id])
            ->with('work_order_created', true)
            ->with('work_order_id', $wo->id)
            ->with('work_order_no', $wo->work_order_no)
            ->with('work_order_title', $wo->title)
            ->with('success', 'Work Order ' . $wo->work_order_no . ' created successfully!');
    }

    /**
     * Helper method to format address
     */
    private function formatAddress($business): string
    {
        $addressParts = [];

        if (!empty($business->address1)) {
            $addressParts[] = $business->address1;
        }
        if (!empty($business->address2)) {
            $addressParts[] = $business->address2;
        }

        return implode(', ', $addressParts);
    }

    public function print($id)
    {
        $workOrder = WorkOrder::with(['workOrderType', 'items', 'customer', 'vendor', 'assignee', 'warehouse'])
            ->findOrFail($id);

        // Get business info as fallback
        $business = $this->getBusinessInfo();

        return view('work-orders.print', compact('workOrder', 'business'));
    }

    public function download($id)
    {
        try {
            $workOrder = WorkOrder::with(['workOrderType', 'items', 'customer', 'vendor', 'assignee', 'warehouse'])
                ->findOrFail($id);

            $business = $this->getBusinessInfo();

            // Use the same view for PDF
            $pdf = PDF::loadView('work-orders.print', compact('workOrder', 'business'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

            return $pdf->download('work-order-' . $workOrder->work_order_no . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF. Please try again.');
        }
    }

    private function getBusinessInfo()
    {
        // Get the business from the authenticated user
        $business = \App\Models\Business::where('id', $this->businessId)->first();

        if ($business) {
            return [
                'name' => $business->name ?? config('app.name'),
                'logo' => $business->business_logo ?? null,
                'tagline' => $business->tagline ?? 'Professional Work Order Management',
                'address' => $business->address1 . ', ' . $business->address2 ?? '',
                'city' => $business->city->name ?? '',
                'state' => $business->state->name ?? '',
                'zip' => $business->post_code ?? '',
                'country' => $business->country->name ?? '',
                'phone' => $business->phone_number ?? '',
                'email' => $business->email ?? '',
                'website' => $business->website ?? '',
                'tax_number' => '',
                'registration_number' => '',
                'footer_text' => 'Thank you for your business!',
            ];
        }

        return [
            'name' => config('app.name', 'Work Order System'),
            'logo' => null,
            'tagline' => 'Professional Work Order Management',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
            'phone' => '',
            'email' => '',
            'website' => '',
            'tax_number' => '',
            'registration_number' => '',
            'footer_text' => 'Thank you for your business!',
        ];
    }

    public function show(WorkOrder $workOrder): View
    {
        $this->authorizeAccess($workOrder);
        $workOrder->load(['workOrderType', 'items.product', 'items.sourceWarehouse', 'customer', 'vendor', 'assignee', 'warehouse', 'creator']);

        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder): View
    {
        $this->authorizeAccess($workOrder);
        $types = WorkOrderType::forBusiness($this->businessId)->where('is_active', true)->get();

        return view('work-orders.edit', array_merge($this->getFormData(), [
            'workOrder'    => $workOrder,
            'types'        => $types,
            'selectedType' => $workOrder->workOrderType,
        ]));
    }

    public function update(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);

        $workOrder->update($request->only([
            'title',
            'description',
            'customer_id',
            'vendor_id',
            'assigned_to',
            'warehouse_id',
            'priority',
            'scheduled_at',
            'due_at',
            'estimated_cost',
            'estimated_hours',
            'instructions',
            'internal_notes',
            'meta'
        ]));

        return back()->with('success', 'Work Order updated.');
    }

    // ─── Status Actions ───
    public function start(WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->start($workOrder);
        return back()->with('success', 'Work order started.');
    }

    public function hold(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->hold($workOrder, $request->input('reason'));
        return back()->with('success', 'Work order put on hold.');
    }

    public function resume(WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->resume($workOrder);
        return back()->with('success', 'Work order resumed.');
    }

    public function progress(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $request->validate([
            'progress'     => 'required|numeric|min:0|max:100',
            'actual_hours' => 'nullable|numeric|min:0',
            'actual_cost'  => 'nullable|numeric|min:0',
        ]);

        $this->service->updateProgress($workOrder, $request->progress, $request->actual_hours, $request->actual_cost);
        return back()->with('success', 'Progress updated.');
    }

    public function complete(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->complete($workOrder, $request->input('completion_notes'));
        return back()->with('success', 'Work order completed.');
    }

    public function cancel(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->cancel($workOrder, $request->input('reason'));
        return back()->with('success', 'Work order cancelled.');
    }

    public function close(WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->close($workOrder);
        return back()->with('success', 'Work order closed.');
    }

    public function reopen(WorkOrder $workOrder): RedirectResponse
    {
        $this->authorizeAccess($workOrder);
        $this->service->reopen($workOrder);
        return back()->with('success', 'Work order reopened.');
    }

    private function getFormData(): array
    {
        return [
            'customers'  => Customer::where('business_id', $this->businessId)->orderBy('name')->get(),
            'vendors'    => Vendor::where('business_id', $this->businessId)->orderBy('name')->get(),
            'users'      => User::where('business_id', $this->businessId)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('business_id', $this->businessId)->where('is_active', true)->get(),
            'products'   => Product::where('business_id', $this->businessId)->orderBy('product_name')->get(),
        ];
    }

    private function authorizeAccess(WorkOrder $wo): void
    {
        abort_if($wo->business_id !== $this->businessId, 403);
    }
}
