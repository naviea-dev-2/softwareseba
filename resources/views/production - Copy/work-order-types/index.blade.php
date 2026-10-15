@extends('inc.master')

@section('head')
    <title>Work Order Types</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1"><b>Work Order Types</b></h4>
                <small class="text-muted">Define custom work order templates for your business.</small>
            </div>
            <a href="{{ route('work-order-types.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Type
            </a>
        </div>

        <div class="row g-3">
            @forelse($types as $type)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{ $type->name }}</h5>
                            @if(!$type->is_active)
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-3">{{ $type->description ?: 'No description.' }}</p>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Slug: <code>{{ $type->slug }}</code></small>
                            <small class="text-muted d-block">Fields: {{ count($type->config['fields'] ?? []) }}</small>
                            <small class="text-muted d-block">Sections: {{ count($type->config['sections'] ?? []) }}</small>
                            <small class="text-muted d-block">Line Items: {{ ($type->config['line_items']['enabled'] ?? false) ? 'Yes' : 'No' }}</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('work-order-types.edit', $type) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('work-order-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this type?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bx bx-cog" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Types Configured</h5>
                <p>Create your first work order type to get started.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection