<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceRowsImport;
use App\Jobs\FinalizeAttendanceImport;
use App\Models\AttendanceImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class AttendanceImportController extends Controller
{
    public function index()
    {
        $imports = AttendanceImport::latest()->paginate(20);

        return view('Hr.attendance.import', compact('imports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:20480',
            ],
        ]);

        $path = $request->file('file')
            ->store('attendance-imports', 'local');

        $batch = AttendanceImport::create([
            'status' => 'pending',
            'file_path' => $path,
        ]);

        try {
            Excel::queueImport(
                new AttendanceRowsImport($batch->id),
                $path,
                'local'
            )->chain([
                new FinalizeAttendanceImport($batch->id),
            ]);

            return redirect()
                ->route('attendance.import')
                ->with('success', 'Import queued successfully. Refresh to see the status.');
        } catch (Throwable $e) {
            $batch->update([
                'status' => 'failed',
                'error_message' => 'Could not queue the import.',
            ]);

            report($e);

            return back()->withErrors([
                'file' => 'Unable to start the import. Please try again.',
            ]);
        }
    }

    public function showErrors(int $id)
    {
        $batch = AttendanceImport::with([
            'rows' => fn ($query) => $query
                ->whereNotNull('errors')
                ->orderBy('row_number'),
        ])->findOrFail($id);

        return view('Hr.attendance.import-errors', compact('batch'));
    }

    public function template()
    {
        $csv = "empID,dutyDate,shiftID,inTime,outTime\n";
        $csv .= "1001,2026-10-12,1,08:05:00,17:10:00\n";
        $csv .= "1002,2026-10-12,2,22:00:00,06:00:00\n";

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header(
                'Content-Disposition',
                'attachment; filename="attendance-template.csv"'
            );
    }
}