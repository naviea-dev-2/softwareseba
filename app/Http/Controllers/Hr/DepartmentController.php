<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $departments = Department::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$request->q.'%')
                ->orWhere('code', 'like', '%'.$request->q.'%')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.departments.index', [
            'departments' => $departments,
            'editing'     => $this->editing($request, Department::class),
        ]);
    }

    public function store(Request $request)
    {
        Department::create($this->validated($request));

        return redirect()->route('departments.index')->with('status', 'Department saved.');
    }

    public function update(Request $request, Department $department)
    {
        $department->update($this->validated($request));

        return redirect()->route('departments.index')->with('status', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
        } catch (QueryException) {
            return back()->withErrors(['delete' => 'This department has employees or designations and cannot be deleted.']);
        }

        return back()->with('status', 'Department deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'code'   => ['required', 'string', 'max:20'],
            'head'   => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);
    }
}
