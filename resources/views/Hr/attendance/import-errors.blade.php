@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Attendance Import Errors #{{ $batch->id }}</h4>

    <p>
        Status: <strong>{{ ucfirst($batch->status) }}</strong>
    </p>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Excel Row</th>
                    <th>Employee ID</th>
                    <th>Duty Date</th>
                    <th>Shift ID</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Validation Errors</th>
                </tr>
            </thead>

            <tbody>
                @forelse($batch->rows as $row)
                    <tr>
                        <td>{{ $row->row_number }}</td>
                        <td>{{ $row->raw_data['empID'] ?? '' }}</td>
                        <td>{{ $row->raw_data['dutyDate'] ?? '' }}</td>
                        <td>{{ $row->raw_data['shiftID'] ?? '' }}</td>
                        <td>{{ $row->raw_data['inTime'] ?? '' }}</td>
                        <td>{{ $row->raw_data['outTime'] ?? '' }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach(($row->errors ?? []) as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No row-level errors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('attendance.import') }}"
       class="btn btn-secondary">
        Back
    </a>
</div>
@endsection