<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\SalaryHead;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalaryHeadController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $heads = SalaryHead::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.salary_section.salary-heads.index', [
            'heads'   => $heads,
            'editing' => $this->editing($request, SalaryHead::class),
        ]);
    }

    public function store(Request $request)
    {
        SalaryHead::create($this->validated($request));

        return redirect()->route('salary-heads.index')->with('status', 'Salary head saved.');
    }

    public function update(Request $request, SalaryHead $salaryHead)
    {
        // dd($salaryHead);
        $salaryHead->update($this->validated($request, $salaryHead->id));

        return redirect()->route('salary-heads.index')->with('status', 'Salary head updated.');
    }

    public function destroy(SalaryHead $salaryHead)
    {
        try {
            $salaryHead->delete();
        } catch (QueryException) {
            return back()->withErrors(['delete' => 'This head cannot be deleted.']);
        }

        return back()->with('status', 'Salary head deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        // dd($ignoreId);
        return $request->validate([
            'name'   => ['required', 'string', 'max:255',   Rule::unique('salary_heads', 'name')
            ->ignore($ignoreId)
            ->where(fn ($query) => $query->where('business_id', auth()->user()->business->id))],
            'type'   => ['required', 'in:Earning,Deduction'],
            'calc'   => ['required', 'in:Fixed,% of Basic'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);
    }
}
