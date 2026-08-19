@extends('inc.master')

@section('head')

    <title>Manage Depot</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h4 class="mb-1">
                    <b>Depot Management</b>
                </h4>

                <small class="text-muted">
                    Manage all depots
                </small>
            </div>

            <a href="{{ route('depots.create') }}"
               class="btn btn-primary">

                <i class="bx bx-plus"></i>
                Add Depot

            </a>

        </div>


        {{-- SUCCESS MESSAGE --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ERROR MESSAGE --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Depot List
                    </h5>

                    <span class="badge bg-primary">
                        {{ $depots->total() }} Total
                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <thead>

                        <tr>

                            <th width="50">#</th>

                            <th>Code</th>

                            <th>Depot Name</th>

                            <th>Super Depot</th>

                            <th>Manager</th>

                            <th>Phone</th>

                            <th>Area</th>

                            <th>Dealers</th>

                            <th>Status</th>

                            <th width="130">Action</th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($depots as $depot)

                            <tr>

                                <td>
                                    {{ $depots->firstItem() + $loop->index }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $depot->code }}
                                    </strong>
                                </td>


                                <td>
                                    {{ $depot->name }}
                                </td>


                                <td>

                                    {{ $depot->superDepot?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $depot->manager?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $depot->phone ?? '-' }}

                                </td>


                                <td>

                                    {{ $depot->area ?? '-' }}

                                </td>


                                <td>

                                    <span class="badge bg-info">

                                        {{ $depot->dealers_count }}

                                    </span>

                                </td>


                                <td>

                                    @if($depot->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <a href="{{ route('depots.edit', $depot) }}"
                                       class="btn btn-sm btn-info"
                                       title="Edit">

                                        <i class="bx bx-edit"></i>

                                    </a>


                                    <form action="{{ route('depots.destroy', $depot) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this depot?')">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-4">

                                    <i class="bx bx-building fs-1 text-muted"></i>

                                    <p class="mb-0">
                                        No depot found.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                @if($depots->hasPages())

                    <div class="mt-3">

                        {{ $depots->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection