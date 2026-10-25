<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\LateRule;
use App\Services\AttendanceRecalculator;
use Illuminate\Http\Request;

class LateRuleController extends Controller
{
    use ListsRecords;

    // public function __construct(private AttendanceRecalculator $recalc) {}

    public function index(Request $request)
    {
        $rules = LateRule::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('deduction'), fn ($q) => $q->where('deduction', $request->deduction))
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.late-rules.index', [
            'rules'   => $rules,
            'editing' => $this->editing($request, LateRule::class),
        ]);
    }

    public function store(Request $request)
    {
        LateRule::create($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('late-rules.index')->with('status', 'Late roll rule saved.');
    }

    public function update(Request $request, LateRule $lateRule)
    {
        $lateRule->update($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('late-rules.index')->with('status', 'Late roll rule updated.');
    }

    public function destroy(LateRule $lateRule)
    {
        $lateRule->delete();
        // $this->recalc->recalculateAll();

        return back()->with('status', 'Late roll rule deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'grace_min'  => ['required', 'integer', 'min:0'],
            'late_limit' => ['required', 'integer', 'min:0'],
            'deduction'  => ['required', 'in:Warning,Half Day,Full Day,Salary cut'],
            'status'     => ['required', 'in:Active,Inactive'],
        ]);
    }
}
