@extends('inc.master')

@section('head')
    <title>Work Order Types</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-header h4 {
            font-weight: 700;
            color: #0b2b4a;
            margin: 0;
        }
        .page-header h4 i {
            color: #2a7de1;
            margin-right: 0.5rem;
        }

        .type-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e9edf2;
            transition: all 0.25s ease;
            position: relative;
        }
        .type-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 20px rgba(13, 110, 253, 0.08);
            transform: translateY(-2px);
        }
        .type-card .type-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .type-card .type-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0b2b4a;
        }
        .type-card .type-slug {
            font-size: 0.75rem;
            color: #94a3b8;
            font-family: monospace;
        }
        .type-card .type-description {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .type-card .type-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .type-card .type-meta .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }
        .type-card .type-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .type-card .type-actions .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .drag-handle {
            cursor: move;
            color: #94a3b8;
            font-size: 1.2rem;
        }
        .drag-handle:hover {
            color: #0b2b4a;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-state i {
            font-size: 4rem;
            color: #d0d8e0;
            margin-bottom: 1rem;
        }
        .empty-state h5 {
            color: #1a1a2e;
            font-weight: 600;
        }
        .empty-state p {
            color: #94a3b8;
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f8fafc;
        }
        .sortable-drag {
            opacity: 0.8;
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="page-header">
            <h4><i class="bx bx-cog"></i> Work Order Types</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('work-order-types.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> New Type
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($types->isEmpty())
            <div class="empty-state">
                <i class="bx bx-task"></i>
                <h5>No Work Order Types Found</h5>
                <p>Create your first work order type to start organizing your work orders.</p>
                <a href="{{ route('work-order-types.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Create First Type
                </a>
            </div>
        @else
            <div class="row g-3" id="sortableContainer">
                @foreach($types as $type)
                    <div class="col-md-6 col-lg-4" data-id="{{ $type->id }}">
                        <div class="type-card">
                            <div class="type-header">
                                <div>
                                    <div class="type-name">{{ $type->name }}</div>
                                    <div class="type-slug">/{{ $type->slug }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-badge {{ $type->is_active ? 'active' : 'inactive' }}">
                                        <i class="bx bx-{{ $type->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="drag-handle" title="Drag to reorder">
                                        <i class="bx bx-menu"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="type-description">
                                {{ $type->description ?? 'No description provided.' }}
                            </div>

                            <div class="type-meta">
                                <span class="badge bg-light text-dark">
                                    <i class="bx bx-layer"></i> 
                                    {{ count($type->config['fields'] ?? []) }} fields
                                </span>
                                @if($type->lineItemsEnabled())
                                    <span class="badge bg-info text-white">
                                        <i class="bx bx-list-ul"></i> Line Items
                                    </span>
                                @endif
                                @if(count($type->config['sections'] ?? []) > 0)
                                    <span class="badge bg-secondary text-white">
                                        <i class="bx bx-extension"></i> 
                                        {{ count($type->config['sections']) }} sections
                                    </span>
                                @endif
                            </div>

                            <div class="type-actions">
                                <a href="{{ route('work-order-types.edit', $type->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <form action="{{ route('work-order-types.toggle-status', $type->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $type->is_active ? 'warning' : 'success' }} btn-sm">
                                        <i class="bx bx-{{ $type->is_active ? 'pause' : 'play' }}"></i>
                                        {{ $type->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $type->id }}, '{{ $type->name }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 text-muted" style="font-size:0.85rem;">
                <i class="bx bx-info-circle"></i> Drag cards to reorder. Changes are saved automatically.
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Work Order Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteName"></strong>?</p>
                <p class="text-danger"><i class="bx bx-warning"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteName').textContent = name;
        document.getElementById('deleteForm').action = '/work-order-types/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Sortable - Drag to reorder
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('sortableContainer');
        if (container) {
            Sortable.create(container, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    const order = [];
                    document.querySelectorAll('#sortableContainer .col-md-6').forEach(el => {
                        order.push(el.dataset.id);
                    });

                    fetch('{{ route("work-order-types.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show brief success message
                            const toast = document.createElement('div');
                            toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
                            toast.innerHTML = '<i class="bx bx-check-circle"></i> Order updated successfully!';
                            document.body.appendChild(toast);
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => toast.remove(), 300);
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order:', error);
                    });
                }
            });
        }
    });
</script>
@endsection