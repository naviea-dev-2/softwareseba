
@extends('inc.master')

@section('head')
    <link href="{{ asset('public/assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/sweetalert2.min.css') }}" rel="stylesheet">

    <title>Employee Attendance Management</title>

    <style>
        :root {
            --attendance-primary: #01303f;
            --attendance-accent: #0d6efd;
            --attendance-border: #e5eaf0;
            --attendance-muted: #667085;
            --attendance-bg: #f5f7fb;
        }

        .attendance-page {
            padding: 18px 0 30px;
        }

        .attendance-page .form-control,
        .attendance-page .form-select {
            min-height: 39px;
            border: 1px solid #dce2ea;
            border-radius: 7px;
            font-size: 13px;
        }

        .attendance-page .form-control:focus,
        .attendance-page .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .10);
        }

        .attendance-page .btn {
            border-radius: 7px;
            font-size: 13px;
        }

        .attendance-header {
            background: #fff;
            border: 1px solid var(--attendance-border);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(16, 24, 40, .03);
        }

        .attendance-header h5 {
            color: #101828;
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 5px;
        }

        .attendance-header p {
            color: var(--attendance-muted);
            font-size: 12px;
            margin: 0;
        }

        .attendance-header-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            background: #eaf2ff;
            color: var(--attendance-primary);
            font-size: 19px;
        }

        .attendance-filter-card,
        .attendance-table-card {
            border: 1px solid var(--attendance-border);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(16, 24, 40, .03);
            overflow: visible;
        }

        .attendance-filter-card .card-header,
        .attendance-table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #eef1f5;
            border-radius: 12px 12px 0 0;
            padding: 15px 18px;
        }

        .attendance-filter-card .card-body {
            padding: 18px;
        }

        .attendance-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #344054;
            margin: 0;
        }

        .attendance-section-subtitle {
            font-size: 12px;
            color: var(--attendance-muted);
            margin-top: 4px;
        }

        .filter-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475467;
            margin-bottom: 6px;
        }

        .filter-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #eaf2ff;
            color: var(--attendance-accent);
            border-radius: 9px;
        }

        .attendance-filter-card .select2-container {
            width: 100% !important;
        }

        .attendance-filter-card .select2-container--default
        .select2-selection--single {
            height: 39px !important;
            border: 1px solid #dce2ea !important;
            border-radius: 7px !important;
        }

        .attendance-filter-card .select2-selection__rendered {
            line-height: 37px !important;
            padding-left: 10px !important;
            font-size: 13px;
        }

        .attendance-filter-card .select2-selection__arrow {
            height: 37px !important;
        }

        .attendance-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .attendance-btn-in {
            background: #087f5b;
            border-color: #087f5b;
            color: #fff;
        }

        .attendance-btn-in:hover {
            background: #066849;
            border-color: #066849;
            color: #fff;
        }

        .attendance-btn-out {
            background: #c0392b;
            border-color: #c0392b;
            color: #fff;
        }

        .attendance-btn-out:hover {
            background: #a93226;
            border-color: #a93226;
            color: #fff;
        }

        .attendance-table-card .card-body {
            padding: 16px;
        }

        #myTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        #myTable thead th {
            background: #f8fafc;
            color: #344054;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            padding: 12px 10px;
            border-bottom: 1px solid #e5eaf0;
        }

        #myTable tbody td {
            font-size: 12px;
            color: #475467;
            padding: 12px 10px;
            vertical-align: middle;
        }

        #myTable tbody tr:hover {
            background: #f9fbff;
        }

        .attendance-page .dataTables_wrapper {
            font-size: 13px;
        }

        .attendance-page .dataTables_wrapper .dataTables_filter input,
        .attendance-page .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dce2ea;
            border-radius: 6px;
            padding: 5px 8px;
        }

        .attendance-page .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 5px;
        }

        .attendance-modal .modal-content {
            border: 0;
            border-radius: 12px;
            overflow: visible;
            box-shadow: 0 15px 45px rgba(0, 0, 0, .16);
        }

        .attendance-modal .modal-header {
            min-height: 58px;
            padding: 14px 20px;
            background: var(--attendance-primary);
            color: #fff;
            border-bottom: 0;
        }

        .attendance-modal .modal-title {
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }

        .attendance-modal .modal-body {
            padding: 22px;
        }

        .attendance-modal label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #344054;
            margin-bottom: 7px;
        }

        .attendance-modal .form-control,
        .attendance-modal .select2-container--default
        .select2-selection--single {
            min-height: 40px;
            border-radius: 7px;
            border-color: #dce2ea;
        }

        .attendance-modal .select2-container {
            width: 100% !important;
        }

        .attendance-modal .select2-selection__rendered {
            line-height: 38px !important;
        }

        .attendance-modal .select2-selection__arrow {
            height: 38px !important;
        }

        .attendance-modal .invalid-feedback {
            display: block;
            min-height: 0;
            font-size: 12px;
        }

        .attendance-modal .modal-footer {
            border-top: 1px solid #eef1f5;
            padding: 14px 22px;
        }

        @media (max-width: 767px) {
            .attendance-page {
                padding-top: 10px;
            }

            .attendance-header {
                padding: 14px;
            }

            .attendance-header h5 {
                font-size: 16px;
            }

            .attendance-header .attendance-actions {
                width: 100%;
            }

            .attendance-header .attendance-actions .btn {
                flex: 1;
            }

            .attendance-filter-card .card-body,
            .attendance-table-card .card-body {
                padding: 12px;
            }

            .attendance-modal .modal-body {
                padding: 16px;
            }
        }
    </style>
@endsection

@section('content')
<div class="content-area attendance-page">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="attendance-header d-flex justify-content-between
                    align-items-center flex-wrap gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="attendance-header-icon">
                    <i class="bx bxs-calendar-plus"></i>
                    {{-- <i class="bx bxs-calendar-event"></i> --}}
                </div>

                <div>
                    <h5>Employee Attendance</h5>
                    <p>Manage employee check-in, check-out and working hours.</p>
                </div>
            </div>

            <div class="attendance-actions">
                <button type="button"
                        class="btn attendance-btn-in px-4 py-2"
                        data-bs-toggle="modal"
                        data-bs-target="#inTimeModal">
                    <i class="fa fa-sign-in me-1"></i>
                    In Time
                </button>

                <button type="button"
                        class="btn attendance-btn-out px-4 py-2"
                        data-bs-toggle="modal"
                        data-bs-target="#outTimeModal">
                    <i class="fa fa-sign-out me-1"></i>
                    Out Time
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card attendance-filter-card mt-4 mb-3">

            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center
                            flex-wrap gap-2">

                    <div class="d-flex align-items-center gap-2">
                        <span class="filter-icon">
                            <i class="bx bxs-calendar-event"></i>
                        </span>

                        <div>
                            <h6 class="attendance-section-title">
                                Attendance Filters
                            </h6>
                            <div class="attendance-section-subtitle">
                                Combine multiple filters to find attendance records.
                            </div>
                        </div>
                    </div>

                    <button type="button"
                            class="btn btn-light border btn-sm"
                            id="toggleAttendanceFilters">
                        <i class="fa fa-sliders me-1"></i>
                        Filters
                        <i class="fa fa-chevron-up ms-1"
                           id="filterToggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="card-body" id="attendanceFilterBody">
                <div class="row g-3">

                    {{-- Employee --}}
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="filter_employee" class="filter-label">
                            Employee
                        </label>

                        <select id="filter_employee" class="form-control">
                            <option value="">All Employees</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="filter_date_from" class="filter-label">
                            Date From
                        </label>

                        <input type="text"
                               id="filter_date_from"
                               class="form-control filter-date"
                               placeholder="Start date"
                               autocomplete="off">
                    </div>

                    {{-- Date To --}}
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="filter_date_to" class="filter-label">
                            Date To
                        </label>

                        <input type="text"
                               id="filter_date_to"
                               class="form-control filter-date"
                               placeholder="End date"
                               autocomplete="off">
                    </div>

                    {{-- Shift --}}
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="filter_shift" class="filter-label">
                            Shift
                        </label>

                        <select id="filter_shift" class="form-control">
                            <option value="">All Shifts</option>

                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->shiftName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Time In From --}}
                    <div class="col-6 col-md-3 col-lg-3">
                        <label for="filter_in_from" class="filter-label">
                            Time In From
                        </label>

                        <input type="text"
                               id="filter_in_from"
                               class="form-control filter-time"
                               placeholder="08:00"
                               autocomplete="off">
                    </div>

                    {{-- Time In To --}}
                    <div class="col-6 col-md-3 col-lg-3">
                        <label for="filter_in_to" class="filter-label">
                            Time In To
                        </label>

                        <input type="text"
                               id="filter_in_to"
                               class="form-control filter-time"
                               placeholder="09:00"
                               autocomplete="off">
                    </div>

                    {{-- Time Out From --}}
                    <div class="col-6 col-md-3 col-lg-3">
                        <label for="filter_out_from" class="filter-label">
                            Time Out From
                        </label>

                        <input type="text"
                               id="filter_out_from"
                               class="form-control filter-time"
                               placeholder="17:00"
                               autocomplete="off">
                    </div>

                    {{-- Time Out To --}}
                    <div class="col-6 col-md-3 col-lg-3">
                        <label for="filter_out_to" class="filter-label">
                            Time Out To
                        </label>

                        <input type="text"
                               id="filter_out_to"
                               class="form-control filter-time"
                               placeholder="18:00"
                               autocomplete="off">
                    </div>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between
                            align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        <i class="fa fa-info-circle me-1"></i>
                        Empty fields will be ignored.
                    </small>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button"
                                class="btn btn-light border px-3"
                                id="resetAttendanceFilters">
                            <i class="fa fa-refresh me-1"></i>
                            Reset
                        </button>

                        <button type="button"
                                class="btn btn-primary px-3"
                                id="applyAttendanceFilters">
                            <i class="fa fa-search me-1"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="card attendance-table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between
                            align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="attendance-section-title">
                            Attendance Records
                        </h6>
                        <div class="attendance-section-subtitle">
                            View and manage employee attendance.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle"
                           id="myTable">
                        <thead>
                            <tr>
                                <th>SN.</th>
                                <th>Employee</th>
                                <th>Duty Date</th>
                                <th>Shift</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Working Time</th>
                                <th>Late</th>
                                <th>Overtime</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="refarel_table"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Existing modal partials --}}
@include('Hr.attendance.attentedence_in')
@include('Hr.attendance.attentedence_out')

@endsection

@section('script')
<script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}"></script>

<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Date and time pickers
    |--------------------------------------------------------------------------
    */

    flatpickr('.filter-date', {
        dateFormat: 'Y-m-d',
        allowInput: true
    });

    flatpickr('.filter-time', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true,
        allowInput: true
    });

    // Existing In/Out modal date pickers
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        allowInput: true
    });

    flatpickr('.datetimepicker', {
        static: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true,
        allowInput: true
    });

    /*
    |--------------------------------------------------------------------------
    | Employee Select2: In Time modal
    |--------------------------------------------------------------------------
    */

    $('.employee_select2').select2({
        placeholder: 'Select Employee',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#inTimeModal'),

        ajax: {
            url: '{{ route("select2.employee") }}',
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                    value: $.trim(params.term || '')
                };
            },

            processResults: function (response) {
                return {
                    results: response
                };
            },

            cache: true
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Employee Select2: Out Time modal
    |--------------------------------------------------------------------------
    */

    $('.employee2_select2').select2({
        placeholder: 'Select Employee',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#outTimeModal'),

        ajax: {
            url: '{{ route("select2.employee") }}',
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                    value: $.trim(params.term || '')
                };
            },

            processResults: function (response) {
                return {
                    results: response
                };
            },

            cache: true
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Employee filter Select2
    |--------------------------------------------------------------------------
    */

    $('#filter_employee').select2({
        placeholder: 'All Employees',
        allowClear: true,
        width: '100%',

        ajax: {
            url: '{{ route("select2.employee") }}',
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                    value: $.trim(params.term || '')
                };
            },

            processResults: function (response) {
                return {
                    results: response
                };
            },

            cache: true
        }
    });

    /*
    |--------------------------------------------------------------------------
    | DataTable with server-side filters
    |--------------------------------------------------------------------------
    */

    var datatable = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        autoWidth: false,
        scrollX: true,

        ajax: {
            url: '{{ route("attendance.ajax") }}',
            type: 'POST',
            dataType: 'json',

            data: function (data) {
                data._token = '{{ csrf_token() }}';

                data.employee_id = $('#filter_employee').val();
                data.date_from = $('#filter_date_from').val();
                data.date_to = $('#filter_date_to').val();
                data.shift_id = $('#filter_shift').val();

                data.in_time_from = $('#filter_in_from').val();
                data.in_time_to = $('#filter_in_to').val();
                data.out_time_from = $('#filter_out_from').val();
                data.out_time_to = $('#filter_out_to').val();
            }
        },

        columns: [
            { data: 'id', name: 'id' },
            { data: 'employee_name', name: 'employee_name' },
            { data: 'date', name: 'date' },
            { data: 'shift_name', name: 'shift_name' },
            { data: 'in_time', name: 'in_time' },
            { data: 'out_time', name: 'out_time' },
            { data: 'working_time', name: 'working_time' },
            { data: 'late_time', name: 'late_time' },
            { data: 'over_time', name: 'over_time' },
            {
                data: 'status',
                name: 'status',
                orderable: false
            },
            {
                data: 'options',
                name: 'options',
                orderable: false,
                searchable: false
            }
        ],

        language: {
            processing: 'Loading attendance records...',
            emptyTable: 'No attendance records found.',
            zeroRecords: 'No matching attendance records found.'
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Apply filters
    |--------------------------------------------------------------------------
    */

    $('#applyAttendanceFilters').on('click', function () {

        var dateFrom = $('#filter_date_from').val();
        var dateTo = $('#filter_date_to').val();

        if (dateFrom && dateTo && dateFrom > dateTo) {
            toastr.error(
                'The start date cannot be later than the end date.',
                'Invalid Date Range'
            );
            return;
        }

        var inFrom = $('#filter_in_from').val();
        var inTo = $('#filter_in_to').val();

        if (inFrom && inTo && inFrom > inTo) {
            toastr.error(
                'The Time In From value cannot be later than Time In To.',
                'Invalid Time Range'
            );
            return;
        }

        var outFrom = $('#filter_out_from').val();
        var outTo = $('#filter_out_to').val();

        if (outFrom && outTo && outFrom > outTo) {
            toastr.error(
                'The Time Out From value cannot be later than Time Out To.',
                'Invalid Time Range'
            );
            return;
        }

        datatable.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Reset filters
    |--------------------------------------------------------------------------
    */

    $('#resetAttendanceFilters').on('click', function () {
        $('#filter_employee').val(null).trigger('change');
        $('#filter_date_from, #filter_date_to').val('');
        $('#filter_shift').val('');
        $('#filter_in_from, #filter_in_to').val('');
        $('#filter_out_from, #filter_out_to').val('');

        datatable.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Filter panel toggle
    |--------------------------------------------------------------------------
    */

    $('#toggleAttendanceFilters').on('click', function () {
        $('#attendanceFilterBody').stop(true, true).slideToggle(150);

        $('#filterToggleIcon').toggleClass(
            'fa-chevron-up fa-chevron-down'
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Enter key applies filters
    |--------------------------------------------------------------------------
    */

    $('.attendance-filter-card input').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#applyAttendanceFilters').trigger('click');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Delete attendance
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.del_hr_data', function (e) {
        e.preventDefault();

        var action = $(this).attr('data-action');

        if (!action) {
            return;
        }

        Swal.fire({
            title: @json(__('lang.are_you_sure')),
            text: @json(__('lang.you_wont_be_able_to_revert_this')),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: @json(__('lang.yes_delete_it')),
            cancelButtonText: @json(__('lang.cancel'))
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = action;
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Clear validation errors
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input change',
        '.in_data_form .form-control, .out_data_form .form-control',
        function () {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').empty();
            $(this).next('.invalid-feedback').empty();
        }
    );

    $('#inTimeModal, #outTimeModal').on('hidden.bs.modal', function () {
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').empty();
    });

    /*
    |--------------------------------------------------------------------------
    | AJAX attendance form submissions
    |--------------------------------------------------------------------------
    */

    function submitAttendanceForm(form, modalSelector) {

        var submitButton = form.find('button[type="submit"]');
        var originalButtonHtml = submitButton.html();

        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').empty();

        submitButton.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span> Saving...'
        );

        $.ajax({
            method: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',

            success: function (response) {

                if (Number(response.status) === 1) {

                    $(modalSelector).modal('hide');

                    toastr.success(
                        response.success || 'Attendance saved successfully.',
                        'Success',
                        {
                            timeOut: 3000
                        }
                    );

                    form[0].reset();

                    form.find('select').val(null).trigger('change');

                    // Refresh the table without losing the current page.
                    datatable.ajax.reload(null, false);

                    return;
                }

                if (response.errors) {
                    $.each(response.errors, function (key, messages) {

                        var field = form.find(
                            '[name="' + key + '"]'
                        ).first();

                        if (!field.length) {
                            return;
                        }

                        field.addClass('is-invalid');

                        var message = Array.isArray(messages)
                            ? messages[0]
                            : messages;

                        var feedback = field.siblings('.invalid-feedback');

                        if (!feedback.length) {
                            feedback = field.parent().find('.invalid-feedback');
                        }

                        if (feedback.length) {
                            feedback.first().text(message);
                        } else {
                            field.after(
                                $('<div>')
                                    .addClass('invalid-feedback d-block')
                                    .text(message)
                            );
                        }
                    });
                }

                if (response.error) {
                    toastr.error(response.error, 'Error');
                }
            },

            error: function (xhr) {

                if (xhr.status === 422 && xhr.responseJSON
                    && xhr.responseJSON.errors) {

                    $.each(xhr.responseJSON.errors, function (key, messages) {
                        var field = form.find(
                            '[name="' + key + '"]'
                        ).first();

                        if (field.length) {
                            field.addClass('is-invalid');

                            var message = Array.isArray(messages)
                                ? messages[0]
                                : messages;

                            field.after(
                                $('<div>')
                                    .addClass('invalid-feedback d-block')
                                    .text(message)
                            );
                        }
                    });

                } else {
                    toastr.error(
                        'Unable to save attendance. Please try again.',
                        'Request Failed'
                    );
                }
            },

            complete: function () {
                submitButton.prop('disabled', false)
                    .html(originalButtonHtml);
            }
        });
    }

    $('.in_data_form').on('submit', function (e) {
        e.preventDefault();
        submitAttendanceForm($(this), '#inTimeModal');
    });

    $('.out_data_form').on('submit', function (e) {
        e.preventDefault();
        submitAttendanceForm($(this), '#outTimeModal');
    });

});
</script>
@endsection
