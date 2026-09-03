<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\QualityInspection;
use App\Models\Production\QualityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QualityReportController extends Controller
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
        $reports = QualityReport::with('inspection')
            ->where('user_id', $this->businessId)
            ->latest()
            ->paginate(20);

        return view(
            'production.quality-reports.index',
            compact('reports')
        );
    }

    public function create()
    {
        $inspections = QualityInspection::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.quality-reports.create',
            compact('inspections')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_number' => 'required|unique:quality_reports,report_number',
            'inspection_id' => 'nullable',
            'report_period_start' => 'nullable|date',
            'report_period_end' => 'nullable|date',
            'total_inspected' => 'nullable|numeric',
            'total_passed' => 'nullable|numeric',
            'total_failed' => 'nullable|numeric',
            'pass_rate' => 'nullable|numeric',
        ]);

        QualityReport::create([
            'user_id' => $this->businessId,
            ...$validated,
            'total_inspected' => $validated['total_inspected'] ?? 0,
            'total_passed' => $validated['total_passed'] ?? 0,
            'total_failed' => $validated['total_failed'] ?? 0,
        ]);

        return redirect()
            ->route('production.quality-reports.index')
            ->with('success', 'Quality report created successfully.');
    }

    public function show($id)
    {
        $report = QualityReport::where(
            'user_id',
            $this->businessId
        )
            ->with('inspection')
            ->findOrFail($id);

        return view(
            'production.quality-reports.show',
            compact('report')
        );
    }

    public function edit($id)
    {
        $report = QualityReport::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $inspections = QualityInspection::where(
            'user_id',
            $this->businessId
        )->latest()->get();

        return view(
            'production.quality-reports.edit',
            compact('report', 'inspections')
        );
    }

    public function update(Request $request, $id)
    {
        $report = QualityReport::where(
            'user_id',
            $this->businessId
        )->findOrFail($id);

        $validated = $request->validate([
            'report_number' => 'required|unique:quality_reports,report_number,' . $id,
            'inspection_id' => 'nullable',
            'report_period_start' => 'nullable|date',
            'report_period_end' => 'nullable|date',
            'total_inspected' => 'nullable|numeric',
            'total_passed' => 'nullable|numeric',
            'total_failed' => 'nullable|numeric',
            'pass_rate' => 'nullable|numeric',
        ]);

        $report->update($validated);

        return redirect()
            ->route('production.quality-reports.index')
            ->with('success', 'Quality report updated successfully.');
    }

    public function destroy($id)
    {
        QualityReport::where(
            'user_id',
            $this->businessId
        )
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('production.quality-reports.index')
            ->with('success', 'Quality report deleted successfully.');
    }
}