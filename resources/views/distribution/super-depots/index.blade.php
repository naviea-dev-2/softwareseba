@extends('inc.master')

@section('head')

    <title>Super Depot</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4>
                <b>Super Depot</b>
            </h4>

            <a href="{{ route('super-depots.create') }}"
               class="btn btn-primary">

                <i class="bx bx-plus"></i>
                Add Super Depot

            </a>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>

                        <tr>

                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Manager</th>
                            <th>Phone</th>
                            <th>District</th>
                            <th>Depots</th>
                            <th>Status</th>
                            <th width="150">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($superDepots as $superDepot)

                            <tr>
                                <td>
                                    {{ $superDepots->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    {{ $superDepot->code }}
                                </td>

                                <td>
                                    {{ $superDepot->name }}
                                </td>

                                <td>
                                    {{ $superDepot->manager?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $superDepot->phone ?? '-' }}
                                </td>

                                <td>
                                    {{ $superDepot->district ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $superDepot->depots_count }}
                                    </span>
                                </td>

                                <td>

                                    @if($superDepot->status)

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

                                    <a href="{{ route('super-depots.edit', $superDepot) }}"
                                       class="btn btn-sm btn-info">

                                        <i class="bx bx-edit"></i>

                                    </a>


                                    <form action="{{ route('super-depots.destroy', $superDepot) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this Super Depot?')">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center">

                                    No Super Depot found.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $superDepots->links() }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection