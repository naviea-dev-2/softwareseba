```blade
@extends('inc.master')

@section('head')
<title>Manage Employee</title>

<style>
    .employee-tabs {
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 20px;
}

.employee-tabs .nav-link {
    color: #555;
    font-weight: 600;
    padding: 12px 20px;
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
}

.employee-tabs .nav-link.active {
    color: #0d6efd;
    background: transparent;
    border-bottom-color: #0d6efd;
}

.employee-tabs .nav-link:hover {
    color: #0d6efd;
}

    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endsection

@section('content')

<div class="content-area">

    {{-- Page Header --}}
    <div class="container-fluid mt-2"
         style="background:#ffffff; min-height:55px; padding:13px 25px;">

        <div class="d-flex justify-content-between align-items-center">

            <h5 style="font-size:0.875rem; margin:0;">
                Employee
            </h5>

            <a href="{{ route('employee.create') }}"
               class="btn btn-primary">
                Add Employee
            </a>

        </div>
    </div>

    {{-- Employee Table --}}
    <div class="row" style="padding-top:24px;">

        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    {{-- Status Tabs --}}
                    <ul class="nav nav-tabs employee-tabs" id="employeeStatusTabs">

                        <li class="nav-item">
                            <a href="#" class="nav-link" data-status="" role="button">
                                All Employees
                                <span class="badge text-bg-secondary rounded-pill ms-1"
                                    id="all_count">0</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-status="1" role="button">
                                Active Employees
                                <span class="badge text-bg-success rounded-pill ms-1"
                                    id="active_count">0</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link" data-status="0" role="button">
                                Inactive Employees
                                <span class="badge text-bg-danger rounded-pill ms-1"
                                    id="inactive_count">0</span>
                            </a>
                        </li>

                    </ul>
                    {{-- <ul class="nav nav-tabs employee-tabs"
                        id="employeeStatusTabs">

                        <li class="nav-item">
                            <a href="#"
                               class="nav-link"
                               data-status=""
                               role="button">
                                All Employees
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                               class="nav-link active"
                               data-status="1"
                               role="button">
                                Active Employees
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                               class="nav-link"
                               data-status="0"
                               role="button">
                                Inactive Employees
                            </a>
                        </li>

                    </ul> --}}

                    {{-- DataTable --}}
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered dt-responsive nowrap"
                               cellspacing="0"
                               width="100%"
                               id="employeetable">

                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Image</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department Name</th>
                                    <th>Designation Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Basic Salary</th>
                                    <th>Join Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('script')

<link href="{{ asset('public/assets/css/sweetalert2.min.css') }}"
      rel="stylesheet">

<script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}"></script>

<script>
$(document).ready(function () {

    // Default status: all employees
    let selectedStatus = '1';


    // Initialize DataTable
    let datatable = $('#employeetable').DataTable({

        processing: true,
        serverSide: true,

        order: [[0, 'desc']],

        ajax: {
            url: "{{ route('employee.ajax') }}",
            type: "POST",

            data: function (data) {
                data._token = "{{ csrf_token() }}";
                data.status = selectedStatus;
            },
            dataSrc: function (json) {
                $('#all_count').text(json.counts.all);
                $('#active_count').text(json.counts.active);
                $('#inactive_count').text(json.counts.inactive);

                return json.data;
            }
        },

        columns: [
            { data: 'id' },
            { data: 'image', orderable: false },
            { data: 'employee_code' },
            { data: 'employee_name' },
            { data: 'department' },
            { data: 'designation' },
            { data: 'email' },
            { data: 'mobile' },
            { data: 'salary' },
            { data: 'join_date' },
            { data: 'status', orderable: false },
            { data: 'options', orderable: false }
        ],

        columnDefs: [
            {
                targets: [1, 10, 11],
                orderable: false
            }
        ]

    });

    // Switch employee status tabs
    $('#employeeStatusTabs').on('click', '.nav-link', function (e) {
        e.preventDefault();

        $('#employeeStatusTabs .nav-link').removeClass('active');
        $(this).addClass('active');

        selectedStatus = $(this).attr('data-status');

        datatable.ajax.reload(null, true);
    });
    $(document).on('click', '.change-employee-status', function () {
        const button = $(this);
        const id = button.data('id');
        const currentStatus = Number(button.attr('data-status'));

        const newStatus = currentStatus === 1 ? 0 : 1;

        Swal.fire({
            title: 'Are you sure?',
            text: newStatus === 1
                ? 'Do you want to activate this employee?'
                : 'Do you want to deactivate this employee?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change status',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            console.log(result);
            if (!result.value) {
                return;
            }

            button.prop('disabled', true);
            console.log("ss");
            $.ajax({
                url: "{{ route('employee.changeStatus') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: currentStatus
                },

                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });

                    // Refresh rows and all/active/inactive counts.
                    datatable.ajax.reload(null, false);
                },

                error: function (xhr) {
                    const message = xhr.responseJSON?.message
                        || 'Unable to change employee status.';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });

                    datatable.ajax.reload(null, false);
                },

                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    });
    // Delete employee
    $(document).on('click', '.del_hr_data', function () {

        let action = $(this).attr('data-action');

        Swal.fire({
            title: '{{ __("lang.are_you_sure") }}',
            text: '{{ __("lang.you_wont_be_able_to_revert_this") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("lang.yes_delete_it") }}',
            cancelButtonText: '{{ __("lang.cancel") }}'
        }).then(function (result) {

            if (result.value) {
                window.location = action;
            }

        });

    });

});
</script>

@endsection
```