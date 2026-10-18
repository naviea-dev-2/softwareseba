@extends('inc.master')
@section('head')
<title>Product Management</title>
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .pm-page {
        max-width: 1600px;
        margin: auto;
        padding: 26px 24px 50px;
        color: #102748;
        font-family: inherit
    }

    .pm-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 26px
    }

    .pm-brand {
        display: flex;
        align-items: center;
        gap: 20px
    }

    .pm-logo {
        width: 76px;
        height: 76px;
        border-radius: 16px;
        background: #e7f0ff;
        display: grid;
        place-items: center;
        color: #1d63ec;
        font-size: 37px;
        flex-shrink: 0
    }

    .pm-top h1 {
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -.7px;
        margin: 0;
        color: #102748
    }

    .pm-top p {
        font-size: 18px;
        color: #526d90;
        margin: 3px 0 0
    }

    .pm-top-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap
    }

    .pm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border-radius: 9px;
        padding: 12px 21px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid #d4e0f0;
        transition: .15s
    }

    .pm-btn-primary {
        background: #2465ee;
        color: #fff !important;
        border-color: #2465ee
    }

    .pm-btn-primary:hover {
        background: #1552d7
    }

    .pm-btn-light {
        background: white;
        color: #142c4c !important
    }

    .pm-btn-light:hover {
        background: #f4f8ff
    }

    .pm-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 25px
    }

    .pm-stat {
        border-radius: 11px;
        border: 1px solid;
        padding: 24px 22px;
        display: flex;
        align-items: center;
        gap: 23px;
        min-height: 139px
    }

    .pm-stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 30px;
        flex-shrink: 0
    }

    .pm-stat-label {
        font-size: 15px;
        font-weight: 650;
        white-space: nowrap
    }

    .pm-stat-number {
        font-size: 33px;
        line-height: 1.2;
        font-weight: 800;
        color: #112a4d;
        margin: 4px 0
    }

    .pm-stat-note {
        font-size: 13px;
        color: #536d8c
    }

    .pm-stat-blue {
        background: #f0f7ff;
        border-color: #d4e8ff
    }

    .pm-stat-blue .pm-stat-icon {
        background: #dfedff;
        color: #2068ed
    }

    .pm-stat-green {
        background: #f1fbf6;
        border-color: #d7f1e2
    }

    .pm-stat-green .pm-stat-icon {
        background: #d7f7e4;
        color: #07924b
    }

    .pm-stat-yellow {
        background: #fffbef;
        border-color: #fff0c8
    }

    .pm-stat-yellow .pm-stat-icon {
        background: #fff1d0;
        color: #e59400
    }

    .pm-stat-red {
        background: #fff4f4;
        border-color: #ffdada
    }

    .pm-stat-red .pm-stat-icon {
        background: #ffe0e0;
        color: #d32222
    }

    .pm-panel {
        background: #fff;
        border: 1px solid #e0e8f2;
        border-radius: 16px;
        box-shadow: 0 8px 28px rgba(30, 60, 90, .055);
        padding: 22px;
        margin-bottom: 20px
    }

    .pm-panel-head {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 20px
    }

    .pm-panel-head .pm-logo {
        width: 45px;
        height: 45px;
        font-size: 22px;
        border-radius: 12px
    }

    .pm-panel-head h2 {
        font-size: 23px;
        font-weight: 800;
        margin: 0
    }

    .pm-panel-head p {
        color: #597393;
        font-size: 14px;
        margin: 3px 0 0
    }

    .pm-filters {
        display: grid;
        grid-template-columns: minmax(250px, 2.5fr) repeat(3, minmax(135px, 1fr)) 138px;
        gap: 13px;
        margin-bottom: 20px
    }

    .pm-input-wrap {
        position: relative
    }

    .pm-input-wrap>i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #7086a5
    }

    .pm-filters .form-control,
    .pm-filters .form-select {
        height: 51px;
        border: 1px solid #d2dfed;
        border-radius: 9px;
        color: #193455;
        background-color: #fff;
        font-size: 14px;
        box-shadow: none
    }

    .pm-input-wrap .form-control {
        padding-left: 49px
    }

    .pm-filters .form-control:focus,
    .pm-filters .form-select:focus {
        border-color: #4384f4;
        box-shadow: 0 0 0 3px rgba(36, 101, 238, .1)
    }

    .pm-table-wrap {
        border: 1px solid #e1e8f2;
        border-radius: 9px;
        overflow: hidden
    }

    .pm-table {
        width: 100% !important;
        margin: 0 !important
    }

    .pm-table thead th {
        background: #2b435e !important;
        color: #fff !important;
        font-size: 12px !important;
        letter-spacing: .03em;
        text-transform: uppercase;
        border: 0 !important;
        padding: 17px 14px !important;
        white-space: nowrap
    }

    .pm-table tbody td {
        font-size: 14px;
        color: #142d50;
        vertical-align: middle;
        padding: 13px 14px !important;
        border-bottom: 1px solid #e7edf5 !important
    }

    .pm-table tbody tr:nth-child(odd) {
        background: #f8fbff
    }

    .pm-table tbody tr:hover {
        background: #eef5ff !important
    }

    .pm-table tbody tr:last-child td {
        border-bottom: 0 !important
    }

    .pm-table img {
        max-width: 56px;
        max-height: 56px;
        object-fit: contain;
        border-radius: 6px
    }

    .pm-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 650
    }

    .pm-status:before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: currentColor
    }

    .pm-status-active {
        background: #e4f9ec;
        color: #07884a
    }

    .pm-status-inactive {
        background: #fff2d8;
        color: #b97800
    }

    .pm-status-unknown {
        background: #edf1f5;
        color: #6c7b8d
    }

    .pm-table .btn,
    .pm-table a.btn {
        font-size: 12px !important;
        font-weight: 650 !important;
        border-radius: 7px !important;
        padding: 8px 11px !important;
        margin: 2px !important;
        box-shadow: none !important
    }

    .pm-table .btn-primary,
    .pm-table .btn-info {
        background: #e8f1ff !important;
        color: #1762e5 !important;
        border-color: transparent !important
    }

    .pm-table .btn-danger {
        background: #ffebeb !important;
        color: #d52222 !important;
        border-color: transparent !important
    }

    .pm-table .btn-secondary {
        background: #edf2f8 !important;
        color: #284360 !important;
        border-color: transparent !important
    }

    .pm-page .dataTables_wrapper .row:first-child {
        display: none !important
    }

    .pm-page .dataTables_wrapper .row:last-child {
        margin-top: 17px;
        align-items: center
    }

    .pm-page .dataTables_info {
        color: #506b8c !important;
        font-size: 13px !important;
        padding-top: 8px !important
    }

    .pm-page .dataTables_paginate .pagination {
        gap: 5px;
        justify-content: flex-end
    }

    .pm-page .page-link {
        border: 1px solid #dce5f0 !important;
        color: #19365b !important;
        border-radius: 7px !important;
        min-width: 38px;
        text-align: center;
        padding: 8px 11px !important
    }

    .pm-page .page-item.active .page-link {
        background: #2465ee !important;
        border-color: #2465ee !important;
        color: white !important
    }

    @media(max-width:1200px) {
        .pm-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        .pm-filters {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        .pm-input-wrap {
            grid-column: span 2
        }
    }

    @media(max-width:650px) {
        .pm-page {
            padding: 15px 10px
        }

        .pm-top h1 {
            font-size: 26px
        }

        .pm-top p {
            font-size: 14px
        }

        .pm-logo {
            width: 55px;
            height: 55px;
            font-size: 27px
        }

        .pm-stats {
            gap: 10px
        }

        .pm-stat {
            padding: 15px 10px;
            gap: 10px;
            min-height: 112px
        }

        .pm-stat-icon {
            width: 42px;
            height: 42px;
            font-size: 20px
        }

        .pm-stat-label {
            font-size: 12px;
            white-space: normal
        }

        .pm-stat-number {
            font-size: 25px
        }

        .pm-stat-note {
            font-size: 11px
        }

        .pm-panel {
            padding: 12px
        }

        .pm-filters {
            gap: 9px
        }

        .pm-filters .pm-search-button {
            grid-column: span 2
        }

        .pm-top-actions {
            width: 100%
        }

        .pm-top-actions .pm-btn {
            flex: 1
        }
    }
</style>
@endsection
@section('content')
<div class="pm-page">
    <header class="pm-top">
        <div class="pm-brand">
            <div class="pm-logo"><i class="bx bx-cube"></i></div>
            <div>
                <h1>Product Management</h1>
                <p>Manage and review all products</p>
            </div>
        </div>
        <div class="pm-top-actions">
            @if(can_p('import.product'))<a class="pm-btn pm-btn-light" href="{{ route('import.product') }}"><i class="bx bx-upload"></i> Import Product</a>@endif
            @if(can_p('product.create'))<a class="pm-btn pm-btn-primary" href="{{ route('product.create') }}"><i class="bx bx-plus"></i> Add New Product</a>@endif
        </div>
    </header>
    @php $productStats = $productStats ?? []; @endphp
    <section class="pm-stats" aria-label="Product statistics">
        <div class="pm-stat pm-stat-blue">
            <div class="pm-stat-icon"><i class="bx bx-cube"></i></div>
            <div>
                <div class="pm-stat-label">Total Products</div>
                <div class="pm-stat-number">{{ isset($productStats['total']) ? number_format($productStats['total']) : '—' }}</div>
                <div class="pm-stat-note">All products</div>
            </div>
        </div>
        <div class="pm-stat pm-stat-green">
            <div class="pm-stat-icon"><i class="bx bx-layer"></i></div>
            <div>
                <div class="pm-stat-label">Active Products</div>
                <div class="pm-stat-number">{{ isset($productStats['active']) ? number_format($productStats['active']) : '—' }}</div>
                <div class="pm-stat-note">Currently active</div>
            </div>
        </div>
        <div class="pm-stat pm-stat-yellow">
            <div class="pm-stat-icon"><i class="bx bx-pause-circle"></i></div>
            <div>
                <div class="pm-stat-label">Inactive Products</div>
                <div class="pm-stat-number">{{ isset($productStats['inactive']) ? number_format($productStats['inactive']) : '—' }}</div>
                <div class="pm-stat-note">Not in use</div>
            </div>
        </div>
        <div class="pm-stat pm-stat-red">
            <div class="pm-stat-icon"><i class="bx bx-error"></i></div>
            <div>
                <div class="pm-stat-label">Out of Stock</div>
                <div class="pm-stat-number">{{ isset($productStats['out_of_stock']) ? number_format($productStats['out_of_stock']) : '—' }}</div>
                <div class="pm-stat-note">No stock available</div>
            </div>
        </div>
    </section>
    <section class="pm-panel">
        <div class="pm-panel-head">
            <div class="pm-logo"><i class="bx bx-list-ul"></i></div>
            <div>
                <h2>Product List</h2>
                <p>View and manage all products</p>
            </div>
        </div>
        <div class="pm-filters">
            <div class="pm-input-wrap"><i class="bx bx-search"></i><input id="pm-search" class="form-control" placeholder="Search product name, code, category or brand..." aria-label="Search products"></div>
            <select id="pm-category" class="form-select" aria-label="Category">
                <option value="">All Categories</option>
            </select>
            <select id="pm-brand" class="form-select" aria-label="Brand">
                <option value="">All Brands</option>
            </select>
            <select id="pm-status" class="form-select" aria-label="Status">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button id="pm-search-button" type="button" class="pm-btn pm-btn-primary pm-search-button"><i class="bx bx-search"></i> Search</button>
        </div>
        <div class="pm-table-wrap table-responsive">
            <table id="dataTable" class="table pm-table align-middle" style="min-width:980px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>
</div>
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    $(function() {
        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: false,
            pageLength: 10,
            dom: 'rt<"row g-2"<"col-md-6"i><"col-md-6"p>>',
            language: {
                emptyTable: 'No products found',
                processing: 'Loading products...'
            },
            ajax: {
                url: @json(route('ajax.products')),
                type: 'POST',
                dataType: 'json',
                data: function(data) {
                    data._token = @json(csrf_token());
                    data.search.value = $('#pm-search').val();
                    // The AJAX controller must apply these filters for them to affect results.
                    data.category_filter = $('#pm-category').val();
                    data.brand_filter = $('#pm-brand').val();
                    data.status_filter = $('#pm-status').val();
                }
            },
            columns: [{
                    data: 'id'
                }, {
                    data: 'thumbnail',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name'
                }, {
                    data: 'code'
                }, {
                    data: 'category_id'
                }, {
                    data: 'brand_id'
                },
                {
                    data: 'status',
                    defaultContent: '',
                    orderable: false,
                    searchable: false,
                    render: function(value, type) {
                        if (type !== 'display') return value || '';
                        if (value === null || value === undefined || value === '') return '<span class="pm-status pm-status-unknown">Unknown</span>';
                        let normalized = String(value).toLowerCase();
                        let active = ['1', 'active', 'enabled', 'true'].includes(normalized);
                        let inactive = ['0', 'inactive', 'disabled', 'false'].includes(normalized);
                        return active ? '<span class="pm-status pm-status-active">Active</span>' : (inactive ? '<span class="pm-status pm-status-inactive">Inactive</span>' : '<span class="pm-status pm-status-unknown">Unknown</span>');
                    }
                },
                {
                    data: 'options',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#pm-search-button').on('click', function() {
            table.ajax.reload();
        });
        $('#pm-search').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                table.ajax.reload();
            }
        });
        $('#pm-category,#pm-brand,#pm-status').on('change', function() {
            table.ajax.reload();
        });
        // Load category/brand choices from existing Select2 lookup endpoints, if available.
        function fillSelect(url, selector) {
            $.ajax({
                url: url,
                dataType: 'json',
                data: {
                    value: ''
                }
            }).done(function(items) {
                const $select = $(selector);
                const seen = new Set();
                (Array.isArray(items) ? items : (items.results || [])).forEach(function(item) {
                    const id = item.id,
                        name = item.text || item.name;
                    if (id !== undefined && name && !seen.has(String(id))) {
                        $select.append(new Option(name, id));
                        seen.add(String(id));
                    }
                });
            });
        }
        fillSelect(@json(route('select2.product.categories')), '#pm-category');
        fillSelect(@json(route('select2.product.brands')), '#pm-brand');
        $(document).on('click', '.del_data', function(e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            Swal.fire({
                title: @json(__('lang.are_you_sure')),
                text: @json(__('lang.you_wont_be_able_to_revert_this')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2465ee',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('lang.yes_delete_it')),
                cancelButtonText: @json(__('lang.cancel'))
            }).then(function(result) {
                if (result.isConfirmed || result.value) window.location = @json(url('product-delete')) + '/' + encodeURIComponent(id);
            });
        });
    });
</script>
@endsection