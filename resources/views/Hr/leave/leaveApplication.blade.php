@extends('inc.master')

@section('head')
{{-- <link href="{{ asset('public/assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"/> --}}
<title>Leave Management</title>
<style>
    :root {
        --lm-brand: #01303f;
        --lm-brand-hover: #02424f;
        --lm-radius: .5rem;
        --lm-border: #e5e9ec;
        --lm-muted: #6c757d;
        --lm-select-h: 38px;
    }

    /* ---------- Layout ---------- */
    .lm-page { background: #f4f6f8; min-height: 100%; }
    .lm-card {
        background: #fff;
        border: 1px solid var(--lm-border);
        border-radius: var(--lm-radius);
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .lm-header { padding: 1rem 1.25rem 0; }
    .lm-title { font-size: 1rem; font-weight: 600; margin: 0 0 .75rem; color: #212529; }

    /* ---------- Tabs ---------- */
    .leave-tabs { border-bottom: 1px solid var(--lm-border); flex-wrap: nowrap; overflow-x: auto; }
    .leave-tabs .nav-link {
        font-size: .875rem;
        color: var(--lm-muted);
        border: 0;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        padding: .6rem 1rem;
        white-space: nowrap;
    }
    .leave-tabs .nav-link:hover { color: var(--lm-brand); }
    .leave-tabs .nav-link.active {
        color: var(--lm-brand);
        font-weight: 600;
        background: transparent;
        border-bottom-color: var(--lm-brand);
    }

    /* ---------- Toolbar / search ---------- */
    .lm-section { padding: 1.25rem; }
    .lm-toolbar { display: flex; justify-content: flex-end; margin-bottom: 1rem; }
    .lm-filter label { font-size: .8125rem; font-weight: 500; margin-bottom: .25rem; color: #495057; }
    .lm-filter .form-control,
    .lm-filter .form-select { font-size: .875rem; }

    /* ---------- Select2 alignment with Bootstrap inputs ---------- */
    .select2-container--default .select2-selection--single {
        height: var(--lm-select-h) !important;
        border: 1px solid #ced4da;
        border-radius: .375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__clear {
        line-height: calc(var(--lm-select-h) - 2px) !important;
        height: calc(var(--lm-select-h) - 2px) !important;
    }

    /* ---------- Tables ---------- */
    .lm-table thead th {
        font-size: .8125rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #495057;
        background: #f8f9fa;
        white-space: nowrap;
    }
    .lm-table td { font-size: .875rem; vertical-align: middle; }
    .lm-table .lm-actions { width: 1%; white-space: nowrap; text-align: end; }
    .lm-table .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

    /* ---------- Modals ---------- */
    .modal-header-brand { background-color: var(--lm-brand); }
    .modal-header-brand .modal-title { color: #fff; font-size: 1rem; }
    .modal-header-brand .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    .crud-modal .form-label { font-size: .8125rem; font-weight: 500; margin-bottom: .25rem; }
    .crud-modal .req::after { content: " *"; color: #dc3545; }
    .btn-brand { background: var(--lm-brand); border-color: var(--lm-brand); color: #fff; }
    .btn-brand:hover, .btn-brand:focus { background: var(--lm-brand-hover); border-color: var(--lm-brand-hover); color: #fff; }

    /* ---------- Responsive ---------- */
    @media (max-width: 767.98px) {
        .lm-section { padding: 1rem; }
        .lm-filter .col-md-2, .lm-filter .col-md-3 { width: 100%; }
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

<div class="lm-page">
    <div class="container-fluid pt-3">

        {{-- ===== Page header + tabs ===== --}}
        <div class="lm-card mb-3">
            <div class="lm-header">
                <h1 class="lm-title">Leave Management</h1>
            </div>
            <ul class="nav leave-tabs px-3 mt-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#leaveApplication" type="button" role="tab" aria-selected="true">Leave Application</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaveType" type="button" role="tab" aria-selected="false">Leave Type</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leavePart" type="button" role="tab" aria-selected="false">Leave Part</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaveTagline" type="button" role="tab" aria-selected="false">Leave Tagline</button>
                </li>
            </ul>
        </div>

        <div class="tab-content">

            {{-- ===== LEAVE APPLICATION ===== --}}
            <div class="tab-pane fade show active" id="leaveApplication" role="tabpanel">
                <div class="lm-card">
                    <div class="lm-section">
                        <form id="leaveApplicationSearch" class="row g-3 align-items-end lm-filter mb-3">
                            <div class="col-md-3">
                                <label class="form-label" for="searchEmpID">Employee</label>
                                <select class="form-select" id="searchEmpID" name="empID"></select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="searchLeaveType">Leave Type</label>
                                <select class="form-select" id="searchLeaveType" name="leaveType">
                                    <option value="">All</option>
                                    @foreach($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->description }}">{{ $leaveType->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label req" for="searchFromDate">From Date</label>
                                <input type="date" class="form-control" id="searchFromDate" name="fDate" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label req" for="searchToDate">To Date</label>
                                <input type="date" class="form-control" id="searchToDate" name="tDate" required>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-brand">
                                    <i class="bx bx-search me-1"></i>Search
                                </button>
                            </div>
                        </form>

                        <div class="lm-toolbar">
                            <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#applicationModal">
                                <i class="bx bx-plus me-1"></i>New Application
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table id="leaveApplicationTable" class="table table-hover table-bordered lm-table w-100">
                                <thead>
                                    <tr>
                                        <th>SN.</th><th>Employee</th><th>Leave Type</th><th>Leave Part</th>
                                        <th>From</th><th>To</th><th>Days</th><th>Status</th><th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== LEAVE TYPE ===== --}}
            <div class="tab-pane fade" id="leaveType" role="tabpanel">
                <div class="lm-card">
                    <div class="lm-section">
                        <div class="lm-toolbar">
                            <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#leaveTypeModal">
                                <i class="bx bx-plus me-1"></i>Add Leave Type
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="leaveTypeTable" class="table table-hover table-bordered lm-table w-100">
                                <thead>
                                    <tr>
                                        <th>SN.</th><th>Leave Code</th><th>Description</th>
                                        <th>Total Day</th><th>Total Hour</th><th class="text-end">Actions</th>
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
                                        <td class="lm-actions">
                                            @if($canLeaveTypeEdit)
                                            <button type="button" class="btn btn-outline-primary btn-icon js-edit"
                                                    title="Edit" aria-label="Edit leave type"
                                                    data-id="{{ $leaveType->id }}"
                                                    data-url="{{ route('leaveType.edit') }}"
                                                    data-modal="#leaveTypeModal">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            @endif
                                            @if($canLeaveTypeDelete)
                                            <button type="button" class="btn btn-outline-danger btn-icon js-delete"
                                                    title="Delete" aria-label="Delete leave type"
                                                    data-action="{{ route('leaveType.delete', $leaveType->id) }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== LEAVE PART ===== --}}
            <div class="tab-pane fade" id="leavePart" role="tabpanel">
                <div class="lm-card">
                    <div class="lm-section">
                        <div class="lm-toolbar">
                            <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#leavePartModal">
                                <i class="bx bx-plus me-1"></i>Add Leave Part
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="leavePartTable" class="table table-hover table-bordered lm-table w-100">
                                <thead>
                                    <tr><th>SN.</th><th>Leave Part Name</th><th>Day</th><th class="text-end">Actions</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveParts as $key => $leavePart)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $leavePart->levaePartName }}</td>
                                        <td>{{ $leavePart->day }}</td>
                                        <td class="lm-actions">
                                            @if($canLeavePartEdit)
                                            <button type="button" class="btn btn-outline-primary btn-icon js-edit"
                                                    title="Edit" aria-label="Edit leave part"
                                                    data-id="{{ $leavePart->id }}"
                                                    data-url="{{ route('leavePart.edit') }}"
                                                    data-modal="#leavePartModal">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            @endif
                                            @if($canLeavePartDelete)
                                            <button type="button" class="btn btn-outline-danger btn-icon js-delete"
                                                    title="Delete" aria-label="Delete leave part"
                                                    data-action="{{ route('leavePart.delete', $leavePart->id) }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== LEAVE TAGLINE ===== --}}
            <div class="tab-pane fade" id="leaveTagline" role="tabpanel">
                <div class="lm-card">
                    <div class="lm-section">
                        <div class="lm-toolbar">
                            <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#leaveTaglineModal">
                                <i class="bx bx-plus me-1"></i>Add Tagline
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="leaveTaglineTable" class="table table-hover table-bordered lm-table w-100">
                                <thead>
                                    <tr>
                                        <th>SN.</th><th>Leave Code</th><th>Leave Description</th>
                                        <th>Leave Part</th><th>Leave Part Day</th><th class="text-end">Actions</th>
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
                                        <td class="lm-actions">
                                            @if($canLeaveTaglineEdit)
                                            <button type="button" class="btn btn-outline-primary btn-icon js-edit"
                                                    title="Edit" aria-label="Edit tagline"
                                                    data-id="{{ $leaveTagline->id }}"
                                                    data-url="{{ route('leaveTagline.edit') }}"
                                                    data-modal="#leaveTaglineModal">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            @endif
                                            @if($canLeaveTaglineDelete)
                                            <button type="button" class="btn btn-outline-danger btn-icon js-delete"
                                                    title="Delete" aria-label="Delete tagline"
                                                    data-action="{{ route('leaveTagline.delete', $leaveTagline->id) }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================= MODALS ================= --}}

{{-- Leave Type --}}
<div class="modal fade crud-modal" id="leaveTypeModal" tabindex="-1" aria-labelledby="leaveTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title" id="leaveTypeModalLabel">Leave Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveType.store') }}" class="js-crud-form">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label req" for="ltCode">Leave Code</label>
                        <input type="text" class="form-control" id="ltCode" name="leaveCode" placeholder="ML" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label req" for="ltDesc">Description</label>
                        <input type="text" class="form-control" id="ltDesc" name="description" placeholder="Medical Leave" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label" for="ltDay">Total Day</label>
                        <input type="number" class="form-control" id="ltDay" name="day" placeholder="10" min="0" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label" for="ltHour">Total Hour</label>
                        <input type="number" class="form-control" id="ltHour" name="hour" placeholder="45" min="0" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand"><i class="bx bx-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Part --}}
<div class="modal fade crud-modal" id="leavePartModal" tabindex="-1" aria-labelledby="leavePartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title" id="leavePartModalLabel">Leave Part</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leavePart.store') }}" class="js-crud-form">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-8">
                        <label class="form-label req" for="lpName">Leave Part Name</label>
                        <input type="text" class="form-control" id="lpName" name="levaePartName" placeholder="First Half" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label req" for="lpDay">Day</label>
                        <input type="number" class="form-control" id="lpDay" name="day" placeholder="0.5" step="any" min="0" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand"><i class="bx bx-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Tagline --}}
<div class="modal fade crud-modal" id="leaveTaglineModal" tabindex="-1" aria-labelledby="leaveTaglineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title" id="leaveTaglineModalLabel">Leave Tagline</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveTagline.store') }}" class="js-crud-form">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label req" for="ltgType">Leave Type</label>
                        <select class="form-select" id="ltgType" name="leaveTypeID" required>
                            <option value="">Select one</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->leaveCode }} - {{ $leaveType->description }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label req" for="ltgPart">Leave Part</label>
                        <select class="form-select" id="ltgPart" name="leavePartID" required>
                            <option value="">Select one</option>
                            @foreach($leaveParts as $leavePart)
                                <option value="{{ $leavePart->id }}">{{ $leavePart->levaePartName }} - {{ $leavePart->day }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand"><i class="bx bx-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Application: create --}}
<div class="modal fade crud-modal" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title" id="applicationModalLabel">New Leave Application</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('leaveApplication.store') }}" class="js-crud-form" data-table="leaveApplication">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label req" for="appEmpID">Employee</label>
                        <select class="form-select js-select2" id="appEmpID" name="empID" required></select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label req" for="appLeaveType">Leave Type</label>
                        <select class="form-select" id="appLeaveType" name="leaveTypeID" required>
                            <option value="">Select one</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->leaveCode }} - {{ $leaveType->description }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label req" for="appLeavePart">Leave Part</label>
                        <select class="form-select" id="appLeavePart" name="leavePartID" required>
                            <option value="">Select leave type first</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="appFromDate">From Date</label>
                        <input type="date" class="form-control" id="appFromDate" name="fromDate">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="appToDate">To Date</label>
                        <input type="date" class="form-control" id="appToDate" name="toDate">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label req" for="appPurpose">Purpose</label>
                        <textarea class="form-control" id="appPurpose" name="purpose" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand"><i class="bx bx-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>

{{-- Leave Application: review / approve / reject --}}
<div class="modal fade crud-modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header modal-header-brand">
            <h5 class="modal-title" id="reviewModalLabel">Leave Application Review</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="reviewDetails" class="mb-4"></div>
            <form method="POST" action="{{ route('leave.application.update') }}" class="js-crud-form" data-table="leaveApplication">
                @csrf
                <input type="hidden" name="leaveApplicationID" id="leaveApplicationID">
                <fieldset>
                    <legend class="form-label fs-6 mb-2">Decision</legend>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusApprove" value="1" checked>
                            <label class="form-check-label" for="statusApprove">Approve</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="statusReject" value="-1">
                            <label class="form-check-label" for="statusReject">Reject</label>
                        </div>
                    </div>
                </fieldset>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand"><i class="bx bx-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div></div>
</div>
@endsection

@section('script')
<link href="{{ asset('public/assets/css/sweetalert2.min.css') }}" rel="stylesheet">
<script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}"></script>
<script>
$(function () {
    const TOAST = { showMethod: 'fadeIn', hideMethod: 'fadeOut', timeOut: 5000 };
    const TABLE_IDS = {
        leaveType:       'leaveTypeTable',
        leavePart:       'leavePartTable',
        leaveTagline:    'leaveTaglineTable',
        leaveApplication:'leaveApplicationTable'
    };
    const tables = {};

    /* ---------- DataTables: lazy init per tab ---------- */
    function ensureTable(key) {
        if (tables[key]) return tables[key];

        const opts = key === 'leaveApplication'
            ? {
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('leaveApplication.ajax') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: d => {
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
        return tables[key];
    }

    function getApplicationFilters() {
        return Object.fromEntries($('#leaveApplicationSearch').serializeArray().map(f => [f.name, f.value]));
    }

    /* ---------- Tabs: init table on show, persist in URL hash ---------- */
    const TAB_KEYS = Object.keys(TABLE_IDS);

    $('.leave-tabs [data-bs-toggle="tab"]').on('shown.bs.tab', function () {
        const key = $(this).data('bs-target').slice(1);
        ensureTable(key).columns.adjust();
        history.replaceState(null, '', '#' + key);
    });

    const initial = TAB_KEYS.includes(location.hash.slice(1)) ? location.hash.slice(1) : 'leaveApplication';
    if (initial !== 'leaveApplication') {
        bootstrap.Tab.getOrCreateInstance(document.querySelector(`.leave-tabs [data-bs-target="#${initial}"]`)).show();
    }
    ensureTable(initial);

    /* ---------- Select2 employee lookup ---------- */
    function initEmployeeSelect($el, $parent) {
        $el.select2({
            placeholder: 'Select employee',
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
    initEmployeeSelect($('#appEmpID'), $('#applicationModal'));

    /* ---------- Search ---------- */
    $('#leaveApplicationSearch').on('submit', function (e) {
        e.preventDefault();
        ensureTable('leaveApplication').ajax.reload();
    });

    /* ---------- Dependent select: leave type -> leave part ---------- */
    const PART_PLACEHOLDER = '<option value="">Select leave type first</option>';

    $('#appLeaveType').on('change', function () {
        const $part = $('#appLeavePart');
        if (!this.value) return $part.html(PART_PLACEHOLDER);

        $part.html('<option value="">Loading…</option>');
        $.get("{{ route('leavePartID.callByLeaveTYpe') }}", { id: this.value }, null, 'json')
            .done(res => $part.html(res.html))
            .fail(() => {
                $part.html(PART_PLACEHOLDER);
                toastr.error('Could not load leave parts.', 'Error', TOAST);
            });
    });

    /* ---------- Modal reset ---------- */
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
        if (this.id === 'applicationModal') $('#appLeavePart').html(PART_PLACEHOLDER);
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
                $form.find('[name="id"]').val(res[$form.data('id-field')] ?? $btn.data('id'));
                $form.find('[name]').not('[name="id"]').each(function () {
                    if (this.name in res) $(this).val(res[this.name]).trigger('change');
                });
                bootstrap.Modal.getOrCreateInstance($modal[0]).show();
            })
            .fail(() => toastr.error('Could not load record.', 'Error', TOAST))
            .always(() => $btn.prop('disabled', false));
    });

    /* ---------- Review leave application ---------- */
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
            .html('<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>Saving…');

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
                if (table) table.ajax.reload(null, false);
                else setTimeout(() => location.reload(), 800);
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

    /* ---------- Delete (single handler) ---------- */
    $(document).on('click', '.js-delete', function () {
        const action = $(this).data('action');
        Swal.fire({
            title: '{{ __("lang.are_you_sure") }}',
            text: '{{ __("lang.you_wont_be_able_to_revert_this") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("lang.yes_delete_it") }}',
            cancelButtonText: '{{ __("lang.cancel") }}'
        }).then(result => {
            if (result.isConfirmed || result.value) window.location = action;
        });
    });
});
</script>
@endsection