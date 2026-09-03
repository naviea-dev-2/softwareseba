<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production\Worker;
use App\Traits\BusinessProductionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    use BusinessProductionTrait;

    protected $businessId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->businessId = Auth::user()->business
                ? Auth::user()->business->business_type_id
                : 0;

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = $this->businessQuery(Worker::class);

        $query = $this->applyFilters($query, $request, [
            'search' => [
                'name',
                'employee_code',
                'department',
                'designation',
            ],

            'status' => true,

            'filters' => [
                'shift' => 'shift',
            ],
        ]);

        $workers = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'production.workers.index',
            compact('workers')
        );
    }

    public function create()
    {
        return view('production.workers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'employee_code' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'department' => 'nullable',
            'designation' => 'nullable',
            'skill' => 'nullable',
            'shift' => 'nullable',
            'status' => 'nullable',
        ]);

        Worker::create([
            'user_id' => $this->businessId,
            ...$validated,
        ]);

        return redirect()
            ->route('production.workers.index')
            ->with('success', 'Worker created successfully.');
    }

    public function show($id)
    {
        $worker = $this->findBusinessRecord(
            Worker::class,
            $id
        );

        return view(
            'production.workers.show',
            compact('worker')
        );
    }

    public function edit($id)
    {
        $worker = $this->findBusinessRecord(
            Worker::class,
            $id
        );

        return view(
            'production.workers.edit',
            compact('worker')
        );
    }

    public function update(Request $request, $id)
    {
        $worker = $this->findBusinessRecord(
            Worker::class,
            $id
        );

        $validated = $request->validate([
            'name' => 'required',
            'employee_code' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'department' => 'nullable',
            'designation' => 'nullable',
            'skill' => 'nullable',
            'shift' => 'nullable',
            'status' => 'nullable',
        ]);

        $worker->update($validated);

        return redirect()
            ->route('production.workers.index')
            ->with('success', 'Worker updated successfully.');
    }

    public function destroy($id)
    {
        $this->deleteBusinessRecord(
            Worker::class,
            $id
        );

        return redirect()
            ->route('production.workers.index')
            ->with('success', 'Worker deleted successfully.');
    }
}