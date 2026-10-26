<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Advance;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $advances = Advance::with('employee')
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('advance_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('advance_date', '<=', $request->to))
            ->orderByDesc('advance_date')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.salary_section.advances.index', [
            'advances'  => $advances,
            'employees' => Employee::orderBy('employee_name')->get(),
            'editing'   => $this->editing($request, Advance::class),
        ]);
    }

    public function store(Request $request)
    {
        Advance::create($this->validated($request));

        return redirect()->route('advances.index')->with('status', 'Advance saved.');
    }

    public function update(Request $request, Advance $advance)
    {
        $advance->update($this->validated($request));

        return redirect()->route('advances.index')->with('status', 'Advance updated.');
    }

    public function destroy(Advance $advance)
    {
        $advance->delete();

        return back()->with('status', 'Advance deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'employee_id'  => ['required', 'exists:employees,id'],
            'advance_date' => ['required', 'date'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'reason'       => ['nullable', 'string', 'max:255'],
        ]);
    }
}
