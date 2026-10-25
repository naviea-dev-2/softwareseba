<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\Department;
use App\Models\Hr\Setting\Holiday;
use Illuminate\Http\Request;


class HolidayController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $holidays = Holiday::with('department')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('holiday_date')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.holidays.index', [
            'holidays'    => $holidays,
            'departments' => Department::orderBy('name')->get(),
            'editing'     => $this->editing($request, Holiday::class),
        ]);
    }

    public function store(Request $request)
    {
        Holiday::create($this->validated($request));

        return redirect()->route('holidays.index')->with('status', 'Holiday saved.');
    }

    public function update(Request $request, Holiday $holiday)
    {
        $holiday->update($this->validated($request));

        return redirect()->route('holidays.index')->with('status', 'Holiday updated.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('status', 'Holiday deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'holiday_date'  => ['required', 'date'],
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', 'in:Public,Optional,Weekend'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'status'        => ['required', 'in:Active,Inactive'],
        ]);
    }
}
