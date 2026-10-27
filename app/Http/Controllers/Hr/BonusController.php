<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Bonus;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $bonuses = Bonus::with('employee')
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->filled('month'), fn ($q) => $q->where('payroll_month', $request->month))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('payroll_month')
            ->orderByDesc('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.salary_section.bonuses.index', [
            'bonuses'   => $bonuses,
            'employees' => Employee::orderBy('employee_name')->get(),
            'months'    => Bonus::query()->distinct()->orderByDesc('payroll_month')->pluck('payroll_month'),
            'editing'   => $this->editing($request, Bonus::class),
        ]);
    }

    public function store(Request $request)
    {
        Bonus::create($this->validated($request));

        return redirect()->route('bonuses.index')->with('status', 'Bonus saved.');
    }

    public function update(Request $request, Bonus $bonus)
    {
        $bonus->update($this->validated($request));

        return redirect()->route('bonuses.index')->with('status', 'Bonus updated.');
    }

    public function destroy(Bonus $bonus)
    {
        $bonus->delete();

        return back()->with('status', 'Bonus deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'employee_id'   => ['required', 'exists:employees,id'],
            'payroll_month' => ['required', 'date_format:Y-m'],
            'title'         => ['required', 'string', 'max:255'],
            'calc'          => ['required', 'in:Fixed,% of Basic'],
            'value'         => ['required', 'numeric', 'min:0'],
            'status'        => ['required', 'in:Pending,Approved'],
            'note'          => ['nullable', 'string', 'max:255'],
        ]);
    }
}
