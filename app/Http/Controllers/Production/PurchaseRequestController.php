<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Api\Seller\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;


class PurchaseRequestController extends Controller
{

    protected ?int $sellerId = null;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->sellerId = auth()->user()?->seller_id;
            return $next($request);
        });
    }
    public function index()
    {
        $data = PurchaseRequest::where(
            'user_id',
            $this->sellerId
        )
        ->with('items')
        ->latest()
        ->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }




    public function store(Request $request)
    {
        $request->validate([
            'request_number' => 'required|unique:purchase_requests,request_number',
            'items' => 'required|array'
        ]);

        $purchase = PurchaseRequest::create([
            'user_id' => $this->sellerId,
            'request_number' => $request->request_number,
            'department' => $request->department,
            'required_date' => $request->required_date,
            'priority' => $request->priority ?? 'medium',
            'status' => $request->status
        ]);

        foreach ($request->items as $item) {
            $purchase->items()->create([
                'raw_material_id' => $item['raw_material_id'] ?? null,
                'description' => $item['description'],
                'quantity_requested' => $item['quantity_requested'],
                'quantity_approved' => $item['quantity_approved'],
                'estimated_unit_price' => $item['estimated_unit_price']
            ]);
        }

        return response()->json([
            'message' => 'Purchase request created',
            'data' => $purchase->load('items')
        ]);
    }


    public function show($id)
    {
        return PurchaseRequest::where(
            'user_id',
            $this->sellerId
        )
            ->with('items')
            ->findOrFail($id);
    }




    public function update(Request $request, $id)
    {

        $data = PurchaseRequest::where(
            'user_id',
            $this->sellerId
        )
            ->findOrFail($id);


        $data->update($request->all());


        return response()->json([
            'message' => 'Updated',
            'data' => $data
        ]);
    }





    public function destroy($id)
    {

        PurchaseRequest::where(
            'user_id',
            $this->sellerId
        )
            ->findOrFail($id)
            ->delete();


        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
