<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\Department;
use App\Models\Hr\Employee;
use App\Models\Hr\PayAssignment;
use App\Models\Hr\SalaryHead;
use Illuminate\Http\Request;

class PayAssignmentController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $employees = Employee::with(['department', 'payAssignments'])
            ->when($request->filled('q'), fn ($q) => $q->where('employee_name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
            ->orderBy('employee_name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.salary_section.pay-assignments.index', [
            'employees'   => $employees,
            'heads'       => SalaryHead::where('status', 'Active')->orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'editing'     => $request->filled('edit')
                ? Employee::with('payAssignments')->findOrFail($request->edit)
                : null,
        ]);
    }

    /** Saves every active head for one employee at once. Blank amount = head removed. */
    public function update(Request $request, Employee $employee)
    {
        $data   = $request->validate([
            'values'   => ['nullable', 'array'],
            'values.*' => ['nullable', 'numeric', 'min:0'],
        ]);
        $values = $data['values'] ?? [];

        foreach (SalaryHead::where('status', 'Active')->get() as $head) {
            $value = $values[$head->id] ?? null;

            if ($value === null || $value === '') {
                PayAssignment::where('employee_id', $employee->id)
                    ->where('salary_head_id', $head->id)
                    ->delete();
            } else {
                PayAssignment::updateOrCreate(
                    ['employee_id' => $employee->id, 'salary_head_id' => $head->id],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('pay-assignments.index')->with('status', "Salary heads saved for {$employee->name}.");
    }
}
