<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\Designation;
use App\Models\Hr\Setting\OvertimeSetting;
use App\Services\AttendanceRecalculator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeController extends Controller
{
    use ListsRecords;

    // public function __construct(private AttendanceRecalculator $recalc) {}

    public function index(Request $request)
    {
        $settings = OvertimeSetting::with('designation')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.overtime.index', [
            'settings'     => $settings,
            'designations' => Designation::orderBy('name')->get(),
            'editing'      => $this->editing($request, OvertimeSetting::class),
        ]);
    }

    public function store(Request $request)
    {
        OvertimeSetting::create($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('overtime.index')->with('status', 'Overtime setting saved.');
    }

    public function update(Request $request, OvertimeSetting $overtime)
    {
        $overtime->update($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('overtime.index')->with('status', 'Overtime setting updated.');
    }

    public function destroy(OvertimeSetting $overtime)
    {
        $overtime->delete();
        // $this->recalc->recalculateAll();

        return back()->with('status', 'Overtime setting deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'rate'           => ['required', 'numeric', 'min:0'],
            'max_day'        => ['required', 'numeric', 'min:0'],
            'max_month'      => ['required', 'numeric', 'min:0'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'status'         => ['required', 'in:Active,Inactive'],
        ]);
    }
}
