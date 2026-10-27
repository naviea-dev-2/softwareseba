<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\Department;
use App\Models\Hr\Setting\Designation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $designations = Designation::with('department')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.designations.index', [
            'designations' => $designations,
            'departments'  => Department::orderBy('name')->get(),
            'editing'      => $this->editing($request, Designation::class),
        ]);
    }

    public function store(Request $request)
    {
        Designation::create($this->validated($request));

        return redirect()->route('designations.index')->with('status', 'Designation saved.');
    }

    public function update(Request $request, Designation $designation)
    {
        $designation->update($this->validated($request));

        return redirect()->route('designations.index')->with('status', 'Designation updated.');
    }

    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
        } catch (QueryException) {
            return back()->withErrors(['delete' => 'This designation is used by employees and cannot be deleted.']);
        }

        return back()->with('status', 'Designation deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'level'         => ['required', 'in:Junior,Mid,Senior,Lead,Manager'],
            'status'        => ['required', 'in:Active,Inactive'],
        ]);
    }
    function select2Designation(Request $request){
        $datas = Designation::select('id', 'name')->where('department_id',$request->dept_id)->where("name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name];
        }
        return json_encode($res);
    }
}
