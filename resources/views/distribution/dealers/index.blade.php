@extends('inc.master')

@section('head')

    <title>Manage Dealers</title>

@endsection


@section('content')

<div class="content-area">
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="mb-1">
                    <b>Dealer Management</b>
                </h4>

                <small class="text-muted">
                    Manage all dealers
                </small>

            </div>


            <a href="{{ route('dealers.create') }}"
               class="btn btn-primary">

                <i class="bx bx-plus"></i>
                Add Dealer

            </a>

        </div>



        {{-- SUCCESS --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif



        {{-- ERROR --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif



        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Dealer List
                    </h5>

                    <span class="badge bg-primary">

                        {{ $dealers->total() }}

                        Dealers

                    </span>

                </div>

            </div>



            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <thead>

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Dealer
                            </th>

                            <th>
                                Business
                            </th>

                            <th>
                                Depot
                            </th>

                            <th>
                                Super Depot
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Credit Limit
                            </th>

                            <th>
                                Security Money
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($dealers as $dealer)

                            <tr>

                                <td>

                                    {{ $dealers->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <strong>
                                        {{ $dealer->code }}
                                    </strong>

                                </td>


                                <td>

                                    <div>

                                        <strong>
                                            {{ $dealer->name }}
                                        </strong>

                                        @if($dealer->owner_name)

                                            <br>

                                            <small class="text-muted">

                                                {{ $dealer->owner_name }}

                                            </small>

                                        @endif

                                    </div>

                                </td>


                                <td>

                                    {{ $dealer->business_name ?? '-' }}

                                </td>


                                <td>

                                    {{ $dealer->depot?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $dealer->depot?->superDepot?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $dealer->phone ?? '-' }}

                                </td>


                                <td>

                                    {{ number_format($dealer->credit_limit ?? 0, 2) }}

                                </td>


                                <td>

                                    <strong class="text-success">

                                        {{ number_format($dealer->security_balance ?? 0, 2) }}

                                    </strong>

                                </td>


                                <td>

                                    @if($dealer->status)

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


                                    {{-- SECURITY MONEY --}}

                                    <a href="{{ route('dealers.security-money.index', $dealer) }}"
                                       class="btn btn-sm btn-success"
                                       title="Security Money">

                                        <i class="bx bx-money"></i>

                                    </a>



                                    {{-- EDIT --}}

                                    <a href="{{ route('dealers.edit', $dealer) }}"
                                       class="btn btn-sm btn-info"
                                       title="Edit">

                                        <i class="bx bx-edit"></i>

                                    </a>



                                    {{-- DELETE --}}

                                    <form action="{{ route('dealers.destroy', $dealer) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this dealer?')">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </form>


                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="11"
                                    class="text-center py-4">

                                    <i class="bx bx-user fs-1 text-muted"></i>

                                    <p class="mb-0">
                                        No dealer found.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                @if($dealers->hasPages())

                    <div class="mt-3">

                        {{ $dealers->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection