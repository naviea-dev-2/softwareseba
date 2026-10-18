@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<title>Leave Management</title>
<style>
    label { font-size: 1.2rem; }
    .leave-tabs .nav-link { font-size: .875rem; }
    .modal-header-brand { background-color: #01303f; }
    .modal-header-brand .modal-title { color: #fff; line-height: 18px; }
    .select2-container--default .select2-selection--single { height: 37px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__clear {
        line-height: 37px !important;
        height: 37px !important;
    }
</style>
@endsection

@section('content')
@php
    $canLeaveTypeEdit        = can_p('leaveType.edit');
    $canLeaveTypeDelete      = can_p('leaveType.delete');
    $canLeavePartEdit        = can_p('leavePart.edit');
    $canLeavePartDelete      = can_p('leavePart.delete');
    $canLeaveTaglineEdit     = can_p('leaveTagline.edit');
    $canLeaveTaglineDelete   = can_p('leaveTagline.delete');
    $canLeaveApplicationEdit = can_p('leaveApplication.edit');
@endphp

<div class="content-area">
    <div class="container-fluid mt-2 bg-white px-4 pt-3 pb-2">
        <h5 class="mb-3" style="font-size: .875rem; margin: 0 0 12px;">Leave Management</h5>
        <ul class="nav nav-tabs leave-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#leaveApplication" data-bs-toggle="tab" role="tab">Leave Application</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link " href="#leaveType" data-bs-toggle="tab" role="tab">Leave Type</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#leavePart" data-bs-toggle="tab" role="tab">Leave Part</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#leaveTagline" data-bs-toggle="tab" role="tab">Leave Tagline</a>
            </li>
           
        </ul>
    </div>

    <div class="container-fluid pt-3 tab-content">

        {{-- ============ LEAVE TYPE ============ --}}
        <div class="tab-pane fade " id="leaveType" role="tabpanel">
            <div class="card"><div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveTypeModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <table id="leaveTypeTable" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SN.</th><th>Leave Code</th><th>Description</th>
                            <th>Total Day</th><th>Total Hour</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $key => $leaveType)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leaveType->leaveCode }}</td>
                            <td>{{ $leaveType->description }}</td>
                            <td>{{ $leaveType->day }}</td>
                            <td>{{ $leaveType->hour }}</td>
                            <td class="text-nowrap">
                                @if($canLeaveTypeEdit)
                                <button type="button" class="btn btn-primary js-edit"
                                        data-id="{{ $leaveType->id }}"
                                        data-url="{{ route('leaveType.edit') }}"
                                        data-modal="#leaveTypeModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                @endif
                                @if($canLeaveTypeDelete)
                                <button type="button" class="btn btn-danger js-delete"
                                        data-action="{{ route('leaveType.delete', $leaveType->id) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div></div>
        </div>

        {{-- ============ LEAVE PART ============ --}}
        <div class="tab-pane fade" id="leavePart" role="tabpanel">
            <div class="card"><div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leavePartModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <table id="leavePartTable" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr><th>SN.</th><th>Leave Part Name</th><th>Day</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($leaveParts as $key => $leavePart)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leavePart->levaePartName }}</td>
                            <td>{{ $leavePart->day }}</td>
                            <td class="text-nowrap">
                                @if($canLeavePartEdit)
                                <button type="button" class="btn btn-primary js-edit"
                                        data-id="{{ $leavePart->id }}"
                                        data-url="{{ route('leavePart.edit') }}"
                                        data-modal="#leavePartModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                @endif
                                @if($canLeavePartDelete)
                                <button type="button" class="btn btn-danger js-delete"
                                        data-action="{{ route('leavePart.delete', $leavePart->id) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div></div>
        </div>

        {{-- ============ LEAVE TAGLINE ============ --}}
        <div class="tab-pane fade" id="leaveTagline" role="tabpanel">
            <div class="card"><div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveTaglineModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <table id="leaveTaglineTable" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SN.</th><th>Leave Code</th><th>Leave Description</th>
                            <th>Leave Part</th><th>Leave Part Day</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTaglines as $key => $leaveTagline)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leaveTagline->leaveType?->leaveCode }}</td>
                            <td>{{ $leaveTagline->leaveType?->description }}</td>
                            <td>{{ $leaveTagline->leavePart?->levaePartName }}</td>
                            <td>{{ $leaveTagline->leavePart?->day }}</td>
                            <td class="text-nowrap">
                                @if($canLeaveTaglineEdit)
                                <button type="button" class="btn btn-primary js-edit"
                                        data-id="{{ $leaveTagline->id }}"
                                        data-url="{{ route('leaveTagline.edit') }}"
                                        data-modal="#leaveTaglineModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                @endif
                                @if($canLeaveTaglineDelete)
                                <button type="button" class="btn btn-danger js-delete"
                                        data-action="{{ route('leaveTagline.delete', $leaveTagline->id) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div></div>
        </div>

        {{-- ============ LEAVE APPLICATION ============ --}}
        <div class="tab-pane fade show active" id="leaveApplication" role="tabpanel">
            <div class="card"><div class="card-body">
                <form id="leaveApplicationSearch" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Employee</label>
                        <select class="form-control" id="searchEmpID" name="empID"></select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Leave Type</label>
                        <select class="form-control" name="leaveType">
                            <option value="">-- All --</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->description }}">{{ $leaveType->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date *</label>
                        <input type="date" class="form-control" name="fDate" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date *</label>
                        <input type="date" class="form-control" name="tDate" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger w-100">Search</button>
                    </div>
                </form>

                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applicationModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>

                <table id="leaveApplicationTable" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SN.</th><th>Employee</th><th>Leave Type</th><th>Leave Part</th>
                            <th>From Date</th><th>To Date</th><th>Day</th><th>Status</th><th>Detail</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div></div>
        </div>
    </div>
</div>

{{-- ================= MODALS ================= --}}

{{-- Leave Type --}}
<div class="modal fade crud-modal" id="leaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title">Leave Type</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveType.store') }}" class="js-crud-form" data-id-field="leaveTypeID">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-3">
                        <label class="form-label">Leave Code *</label>
                        <input type="text" class="form-control" name="leaveCode" placeholder="ML" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Description *</label>
                        <input type="text" class="form-control" name="description" placeholder="Medical Leave" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Total Day</label>
                        <input type="number" class="form-control" name="day" placeholder="10" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Total Hour</label>
                        <input type="number" class="form-control" name="hour" placeholder="45" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save pe-2"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Part --}}
<div class="modal fade crud-modal" id="leavePartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title">Leave Part</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leavePart.store') }}" class="js-crud-form" data-id-field="leavePartID">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label">Leave Part Name *</label>
                        <input type="text" class="form-control" name="levaePartName" placeholder="First Half" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Day *</label>
                        <input type="number" class="form-control" name="day" placeholder=".5" step="any" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save pe-2"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Tagline --}}
<div class="modal fade crud-modal" id="leaveTaglineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title">Leave Tagline</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveTagline.store') }}" class="js-crud-form" data-id-field="leaveTaglinID">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label">Leave Type *</label>
                        <select class="form-control" name="leaveTypeID" required>
                            <option value="">-- Select One --</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->leaveCode }} - {{ $leaveType->description }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Leave Part *</label>
                        <select class="form-control" name="leavePartID" required>
                            <option value="">-- Select One --</option>
                            @foreach($leaveParts as $leavePart)
                                <option value="{{ $leavePart->id }}">{{ $leavePart->levaePartName }} - {{ $leavePart->day }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save pe-2"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Application: create --}}
<div class="modal fade crud-modal" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title">Leave Application</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveApplication.store') }}" class="js-crud-form" data-table="leaveApplication">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Employee *</label>
                        <select class="form-control js-select2" id="applicationEmpID" name="empID" required></select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Leave Type *</label>
                        <select class="form-control" id="applicationLeaveType" name="leaveTypeID" required>
                            <option value="">-- Select One --</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->leaveCode }} - {{ $leaveType->description }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Leave Part *</label>
                        <select class="form-control" id="applicationLeavePart" name="leavePartID" required>
                            <option value="">-- Select Leave Type First --</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="fromDate">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="toDate">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Purpose *</label>
                        <textarea class="form-control" name="purpose" rows="2" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save pe-2"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Application: review / approve / reject --}}
<div class="modal fade crud-modal" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title">Leave Application Review</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="reviewDetails" class="mb-3"></div>
            <form method="POST" action="{{ route('leave.application.update') }}" class="js-crud-form" data-table="leaveApplication">
                @csrf
                <input type="hidden" name="leaveApplicationID" id="leaveApplicationID">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="statusApprove" value="1" checked>
                    <label class="form-check-label" for="statusApprove">Approve</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="statusReject" value="-1">
                    <label class="form-check-label" for="statusReject">Reject</label>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save pe-2"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>
@endsection

@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script type="text/javascript">
$(function () {
    const TOAST = { showMethod: 'fadeIn', hideMethod: 'fadeOut', timeOut: 5000 };
    const TAB_KEYS = ['leaveType', 'leavePart', 'leaveTagline', 'leaveApplication'];
    const TABLE_IDS = {
        leaveType: 'leaveTypeTable',
        leavePart: 'leavePartTable',
        leaveTagline: 'leaveTaglineTable',
        leaveApplication: 'leaveApplicationTable'
    };
    const tables = {};

    /* ---------- DataTables: initialise lazily on first tab show ---------- */
    function ensureTable(key) {
        if (!tables[key]) {
            const opts = key === 'leaveApplication'
                ? {
                    order: [[0, 'desc']],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('leaveApplication.ajax') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: function (d) {
                            d._token = "{{ csrf_token() }}";
                            d.filter = getApplicationFilters();
                        }
                    },
                    columns: [
                        { data: 'id' }, { data: 'e_name' }, { data: 'leave_type' },
                        { data: 'leave_part' }, { data: 'from_date' }, { data: 'to_date' },
                        { data: 'day' }, { data: 'status' }, { data: 'options', orderable: false }
                    ]
                }
                : { order: [] };
            tables[key] = $('#' + TABLE_IDS[key]).DataTable(opts);
        }
        return tables[key];
    }

    function getApplicationFilters() {
        return Object.fromEntries($('#leaveApplicationSearch').serializeArray().map(f => [f.name, f.value]));
    }

    /* ---------- Tabs: lazy-load + persist in URL hash ---------- */
    document.querySelectorAll('.leave-tabs [data-bs-toggle="tab"]').forEach(el => {
        el.addEventListener('shown.bs.tab', e => {
            const key = e.target.getAttribute('href').slice(1);
            ensureTable(key).columns.adjust();
            history.replaceState(null, '', '#' + key);
        });
    });

    const initial = TAB_KEYS.includes(location.hash.slice(1)) ? location.hash.slice(1) : 'leaveApplication';
    if (initial !== 'leaveApplication') {
        bootstrap.Tab.getOrCreateInstance(document.querySelector(`.leave-tabs a[href="#${initial}"]`)).show();
    }
    ensureTable(initial);

    /* ---------- Select2 employee lookup ---------- */
    function initEmployeeSelect($el, $parent) {
        $el.select2({
            placeholder: 'Select Employee',
            allowClear: true,
            width: '100%',
            dropdownParent: $parent,
            ajax: {
                url: "{{ route('select2.employee') }}",
                dataType: 'json',
                delay: 250,
                cache: true,
                data: params => ({ value: $.trim(params.term) }),
                processResults: response => ({ results: response })
            }
        });
    }
    initEmployeeSelect($('#searchEmpID'), $('#leaveApplicationSearch'));
    initEmployeeSelect($('#applicationEmpID'), $('#applicationModal'));

    /* ---------- Leave application search ---------- */
    $('#leaveApplicationSearch').on('submit', function (e) {
        e.preventDefault();
        ensureTable('leaveApplication').ajax.reload();
    });

    /* ---------- Dependent select: leave type -> leave part ---------- */
    $('#applicationLeaveType').on('change', function () {
        const $part = $('#applicationLeavePart');
        if (!this.value) {
            $part.html('<option value="">-- Select Leave Type First --</option>');
            return;
        }
        $.get("{{ route('leavePartID.callByLeaveTYpe') }}", { id: this.value }, null, 'json')
            .done(res => $part.html(res.html));
    });

    /* ---------- Modal reset (shared) ---------- */
    function clearFormErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');
    }

    $('.crud-modal').on('hidden.bs.modal', function () {
        const $form = $(this).find('form');
        $form[0].reset();
        $form.find('[name="id"]').val(0);
        $form.find('.js-select2').val(null).trigger('change');
        clearFormErrors($form);
    });

    $('#applicationModal').on('hidden.bs.modal', function () {
        $('#applicationLeavePart').html('<option value="">-- Select Leave Type First --</option>');
    });

    /* ---------- Edit (CRUD tabs) ---------- */
    $(document).on('click', '.js-edit', function () {
        const $btn = $(this);
        const $modal = $($btn.data('modal'));
        const $form = $modal.find('form');

        $btn.prop('disabled', true);
        $.get($btn.data('url'), { id: $btn.data('id') }, null, 'json')
            .done(res => {
                if (res.status != 1) {
                    return toastr.error(res.msg || 'Could not load record.', 'Error', TOAST);
                }
                $form.find('[name="id"]').val(res[$form.data('id-field')]);
                $form.find('[name]').not('[name="id"]').each(function () {
                    if (this.name in res) $(this).val(res[this.name]);
                });
                bootstrap.Modal.getOrCreateInstance($modal[0]).show();
            })
            .fail(() => toastr.error('Could not load record.', 'Error', TOAST))
            .always(() => $btn.prop('disabled', false));
    });

    /* ---------- Review (leave application) ---------- */
    $(document).on('click', '.js-review, .leaveApplicationView, .leaveApplicationEdit', function () {
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.get("{{ route('leave.application.single.view') }}", { id: $btn.data('id') }, null, 'json')
            .done(res => {
                if (!res.viewApplicationData) {
                    return toastr.error(res.msg || 'Could not load application.', 'Error', TOAST);
                }
                $('#reviewDetails').html(res.viewApplicationData);
                $('#leaveApplicationID').val(res.leaveApplicationID);
                $('#statusApprove').prop('checked', true);
                bootstrap.Modal.getOrCreateInstance($('#reviewModal')[0]).show();
            })
            .fail(() => toastr.error('Could not load application.', 'Error', TOAST))
            .always(() => $btn.prop('disabled', false));
    });

    /* ---------- Generic create/update submit ---------- */
    $(document).on('submit', '.js-crud-form', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('[type="submit"]');
        const originalHtml = $btn.html();

        clearFormErrors($form);
        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Processing...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json'
        })
        .done(res => {
            if (res.status == 1) {
                toastr.success(res.success, 'Success', TOAST);
                bootstrap.Modal.getOrCreateInstance($form.closest('.modal')[0]).hide();

                const table = tables[$form.data('table')];
                if (table) {
                    table.ajax.reload(null, false);
                } else {
                    setTimeout(() => location.reload(), 800);
                }
                return;
            }

            $.each(res.errors || {}, (name, msg) => {
                $form.find(`[name="${name}"]`)
                     .addClass('is-invalid')
                     .siblings('.invalid-feedback').text(msg);
            });
            if (res.error) toastr.error(res.error, 'Error', TOAST);
        })
        .fail(() => toastr.error('Something went wrong. Please try again.', 'Error', TOAST))
        .always(() => $btn.prop('disabled', false).html(originalHtml));
    });

    /* ---------- Delete ---------- */
    $(document).on('click', '.js-delete', function () {
        const action = $(this).data('action');
        Swal.fire({
            title: '{{ __("lang.are_you_sure") }}',
            text: '{{ __("lang.you_wont_be_able_to_revert_this") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("lang.yes_delete_it") }}',
            cancelButtonText: '{{ __("lang.cancel") }}'
        }).then(result => {
            if (result.value) window.location = action;
        });
    });

    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('data-id');
        let action = $(this).attr('data-action');
        
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ms-2',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                window.location = action;
            }
        });
    });
});
</script>
@endsection