@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Import Attendance from Excel</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p>
                Required columns: empID, dutyDate, shiftID, inTime.
                outTime is optional.
            </p>

            <a href="{{ route('attendance.import.template') }}"
               class="btn btn-outline-primary mb-3">
                Download CSV Template
            </a>

            <form method="POST"
                  action="{{ route('attendance.import.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Excel / CSV File</label>
                    <input type="file"
                           name="file"
                           id="file"
                           class="form-control"
                           accept=".xlsx,.xls,.csv"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Upload and Import
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Import History</div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Total Rows</th>
                        <th>Error Rows</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($imports as $import)
                        <tr>
                            <td>{{ $import->id }}</td>
                            <td>{{ ucfirst($import->status) }}</td>
                            <td>{{ $import->total_rows }}</td>
                            <td>{{ $import->error_rows }}</td>
                            <td>{{ $import->error_message }}</td>
                            <td>{{ $import->created_at }}</td>
                            <td>
                                @if($import->error_rows > 0)
                                    <a href="{{ route('attendance.import.errors', $import->id) }}"
                                       class="btn btn-sm btn-danger">
                                        View Errors
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No imports found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $imports->links() }}
        </div>
    </div>
</div>
@endsection