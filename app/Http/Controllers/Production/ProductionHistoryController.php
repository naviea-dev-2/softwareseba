<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\ProductionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionHistoryController extends Controller
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
        $history = ProductionHistory::where(
            'user_id',
            $this->businessId
        )
            ->with([
                'productionOrder',
                'workOrder',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'production.production-history.index',
            compact('history')
        );
    }

    public function show($id)
    {
        $historyItem = ProductionHistory::where(
            'user_id',
            $this->businessId
        )
            ->with([
                'productionOrder',
                'workOrder',
            ])
            ->findOrFail($id);

        return view(
            'production.production-history.show',
            compact('historyItem')
        );
    }
}