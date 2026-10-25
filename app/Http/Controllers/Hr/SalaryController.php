<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Employee;
use App\Models\Hr\SalaryRecord;
use App\Services\SalaryCalculator;
use App\Services\SalaryGenerator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalaryController extends Controller
{
    use ListsRecords;

    public function __construct(private SalaryCalculator $calc) {}

    public function index(Request $request)
    {
        $rows = SalaryRecord::with('employee')
            ->when($request->filled('month'), fn ($q) => $q->where('payroll_month', $request->month))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->orderByDesc('payroll_month')
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        $rows->getCollection()->each(fn ($r) => $r->calc = $this->calc->calculate($r));

        return view('Hr.salary_section.salary.index', [
            'rows'      => $rows,
            'months'    => SalaryRecord::query()->distinct()->orderByDesc('payroll_month')->pluck('payroll_month'),
            'employees' => Employee::orderBy('employee_name')->get(),
            'editing'   => $this->editing($request, SalaryRecord::class),
        ]);
    }

    public function store(Request $request)
    {
        app(SalaryGenerator::class)->createRecord($this->validated($request));

        return redirect()->route('salary.index')->with('status', 'Salary record saved.');
    }

    public function update(Request $request, SalaryRecord $salary)
    {
        $salary->update($this->validated($request, $salary->id));
        app(SalaryGenerator::class)->refresh($salary);

        return redirect()->route('salary.index')->with('status', 'Salary record updated.');
    }

    public function destroy(SalaryRecord $salary)
    {
        $salary->delete();

        return back()->with('status', 'Salary record deleted.');
    }

    /** Creates a salary record for every active employee who doesn't have one for the month. */
    public function generate(Request $request)
    {
        $data  = $request->validate(['payroll_month' => ['required', 'date_format:Y-m']]);
        $month = $data['payroll_month'];
        $made  = 0;

        Employee::where('status', 'Active')->get()->each(function (Employee $employee) use ($month, &$made) {
            $exists = SalaryRecord::where('employee_id', $employee->id)->where('payroll_month', $month)->exists();
            if ($exists) {
                return;
            }

            $previous = SalaryRecord::where('employee_id', $employee->id)->orderByDesc('payroll_month')->first();

            app(SalaryGenerator::class)->create($employee, $month, (float) ($previous?->basic ?? 0));
            $made++;
        });

        return redirect()->route('salary.index', ['month' => $month])
            ->with('status', "{$made} salary record(s) generated for {$month}.");
    }

    public function payslip(SalaryRecord $salary)
    {
        $salary->loadMissing('employee');

        return view('salary.payslip', [
            'salary' => $salary,
            'calc'   => $this->calc->calculate($salary),
        ]);
    }

    /** Salary details: one row per salary head that was saved for this month. */
    public function details(SalaryRecord $salary)
    {
        $salary->loadMissing('employee');

        return view('salary.details', [
            'salary'  => $salary,
            'details' => $salary->details()->orderBy('type')->orderBy('id')->get(),
        ]);
    }

    /** Re-saves the details from the employee's current pay assignments. */
    public function refreshDetails(SalaryRecord $salary)
    {
        app(SalaryGenerator::class)->refresh($salary);

        return redirect()->route('salary.details', $salary)->with('status', 'Salary details refreshed from current pay assignments.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'employee_id'   => ['required', 'exists:employees,id'],
            'payroll_month' => [
                'required', 'date_format:Y-m',
                Rule::unique('salary_records', 'payroll_month')
                    ->where(fn ($q) => $q->where('employee_id', $request->input('employee_id')))
                    ->ignore($ignoreId),
            ],
            'basic'   => ['required', 'numeric', 'min:0'],
            'advance' => ['nullable', 'numeric', 'min:0'],
            'due'     => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
