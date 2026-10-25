<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Employee;
use App\Models\Hr\SalaryRecord;
// use App\Models\Payment;
use App\Models\Hr\Setting\PayrollMonth;
use App\Services\SalaryCalculator;
use App\Services\SalaryGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollMonthController extends Controller
{
    use ListsRecords;

    public function __construct(private SalaryCalculator $calc) {}

    public function index(Request $request)
    {
        $months = PayrollMonth::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn ($q) => $q->where('month', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->where('month', '<=', $request->to))
            ->orderByDesc('month')
            ->paginate($this->perPage($request))
            ->withQueryString();

        // Totals per month: salary records generated, net payable and payments
        $months->getCollection()->each(function (PayrollMonth $m) {
            $m->generated  = 0;
            $m->net_total  = 0;
            $m->paid_total  = 0;
            // $records = SalaryRecord::where('payroll_month', $m->month)->get();

            // $m->generated  = $records->count();
            // $m->net_total  = round($records->sum(fn ($r) => $this->calc->calculate($r)['payable']), 2);
            // $m->paid_total = round((float) Payment::where('payroll_month', $m->month)->sum('amount'), 2);
        });

        return view('Hr.setting.payroll-months.index', [
            'months'  => $months,
            'editing' => $this->editing($request, PayrollMonth::class),
        ]);
    }

    public function store(Request $request)
    {
        PayrollMonth::create($this->validated($request));

        return redirect()->route('payroll-months.index')->with('status', 'Month saved.');
    }

    public function update(Request $request, PayrollMonth $payrollMonth)
    {
        $payrollMonth->update($this->validated($request, $payrollMonth->id));

        return redirect()->route('payroll-months.index')->with('status', 'Month updated.');
    }

    public function destroy(PayrollMonth $payrollMonth)
    {
        $payrollMonth->delete();

        return back()->with('status', 'Month deleted. Existing salary records are kept.');
    }

    /** Creates a salary record for every active employee who doesn't have one for this month. */
    public function generate(PayrollMonth $payrollMonth)
    {
        if ($payrollMonth->status !== 'Open') {
            return back()->withErrors(['generate' => "{$payrollMonth->label()} is {$payrollMonth->status}. Open it to generate salary."]);
        }

        $made = 0;

        Employee::where('status', 1)->get()->each(function (Employee $employee) use ($payrollMonth, &$made) {
            $exists = SalaryRecord::where('employee_id', $employee->id)
                ->where('payroll_month', $payrollMonth->month)
                ->exists();
            if ($exists) {
                return;
            }

            $previous = SalaryRecord::where('employee_id', $employee->id)
                ->orderByDesc('payroll_month')
                ->first();

            app(SalaryGenerator::class)->create($employee, $payrollMonth->month, (float) ($previous?->basic ?? 0));
            $made++;
        });

        return redirect()->route('payroll-months.index')
            ->with('status', "{$made} salary record(s) generated for {$payrollMonth->label()}.");
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'month'        => [
                'required', 'date_format:Y-m',
                Rule::unique('payroll_months', 'month')->ignore($ignoreId),
            ],
            'working_days' => ['required', 'integer', 'min:1', 'max:31'],
            'status'       => ['required', 'in:Open,Closed,Locked'],
        ]);
    }
}
