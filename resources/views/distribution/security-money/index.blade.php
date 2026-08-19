@extends('inc.master')

@section('head')

    <title>Security Money - {{ $dealer->name }}</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">


        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="mb-1">
                    <b>Dealer Security Money</b>
                </h4>

                <small class="text-muted">
                    Manage security money transactions for {{ $dealer->name }}
                </small>

            </div>


            <a href="{{ route('dealers.index') }}"
               class="btn btn-secondary">

                <i class="bx bx-arrow-back"></i>
                Back to Dealers

            </a>

        </div>


        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bx bx-check-circle me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        {{-- Dealer Information --}}
        <div class="card mb-3">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="bx bx-user me-1"></i>
                    Dealer Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-2">

                        <small class="text-muted">
                            Code
                        </small>

                        <div class="fw-semibold">
                            {{ $dealer->code }}
                        </div>

                    </div>


                    <div class="col-md-3 mb-2">

                        <small class="text-muted">
                            Name
                        </small>

                        <div class="fw-semibold">
                            {{ $dealer->name }}
                        </div>

                    </div>


                    <div class="col-md-3 mb-2">

                        <small class="text-muted">
                            Phone
                        </small>

                        <div class="fw-semibold">
                            {{ $dealer->phone ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3 mb-2">

                        <small class="text-muted">
                            Status
                        </small>

                        <div>

                            @if($dealer->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- Balance --}}
        <div class="card mb-3">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <small class="text-muted">
                            Current Security Money Balance
                        </small>

                        <h2 class="mb-0 mt-1">

                            {{ number_format($balance, 2) }}

                        </h2>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <span class="badge bg-primary fs-6 px-3 py-2">

                            Security Money

                        </span>

                    </div>

                </div>

            </div>

        </div>



        {{-- Add Transaction --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bx bx-plus-circle me-1"></i>

                    Add Security Money Transaction

                </h5>

            </div>


            <div class="card-body">

                <form action="{{ route('dealers.security-money.store', $dealer) }}"
                      method="POST">

                    @csrf


                    <div class="row">


                        {{-- Transaction Type --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Transaction Type
                                <span class="text-danger">*</span>
                            </label>


                            <select name="transaction_type"
                                    id="transaction_type"
                                    class="form-select @error('transaction_type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Transaction Type --
                                </option>

                                <option value="deposit"
                                    @selected(old('transaction_type') === 'deposit')>

                                    Deposit

                                </option>

                                <option value="refund"
                                    @selected(old('transaction_type') === 'refund')>

                                    Refund

                                </option>

                                <option value="adjustment"
                                    @selected(old('transaction_type') === 'adjustment')>

                                    Adjustment

                                </option>

                            </select>


                            @error('transaction_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- Amount --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Amount

                                <span class="text-danger">*</span>

                            </label>


                            <input type="number"
                                   name="amount"
                                   value="{{ old('amount') }}"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   step="0.01"
                                   min="0.01"
                                   placeholder="0.00"
                                   required>


                            @error('amount')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- Payment Method --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Payment Method
                            </label>


                            <select name="payment_method"
                                    class="form-select @error('payment_method') is-invalid @enderror">

                                <option value="">
                                    -- Select Payment Method --
                                </option>

                                <option value="cash"
                                    @selected(old('payment_method') === 'cash')>

                                    Cash

                                </option>

                                <option value="bank"
                                    @selected(old('payment_method') === 'bank')>

                                    Bank

                                </option>

                                <option value="cheque"
                                    @selected(old('payment_method') === 'cheque')>

                                    Cheque

                                </option>

                                <option value="mobile_banking"
                                    @selected(old('payment_method') === 'mobile_banking')>

                                    Mobile Banking

                                </option>

                                <option value="other"
                                    @selected(old('payment_method') === 'other')>

                                    Other

                                </option>

                            </select>


                            @error('payment_method')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- Reference --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Reference No
                            </label>


                            <input type="text"
                                   name="reference_no"
                                   value="{{ old('reference_no') }}"
                                   class="form-control @error('reference_no') is-invalid @enderror"
                                   placeholder="Cheque / Bank / Payment reference">


                            @error('reference_no')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- Transaction Date --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Transaction Date

                                <span class="text-danger">*</span>

                            </label>


                            <input type="date"
                                   name="transaction_date"
                                   value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                   class="form-control @error('transaction_date') is-invalid @enderror"
                                   required>


                            @error('transaction_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- Remarks --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Remarks
                            </label>


                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control @error('remarks') is-invalid @enderror"
                                      placeholder="Enter transaction remarks">{{ old('remarks') }}</textarea>


                            @error('remarks')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>


                    <div class="mt-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save"></i>

                            Save Transaction

                        </button>

                    </div>

                </form>

            </div>

        </div>



        {{-- Transaction History --}}
        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        <i class="bx bx-transfer me-1"></i>

                        Transaction History

                    </h5>


                    <span class="badge bg-secondary">

                        {{ $transactions->total() }} Transactions

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead>

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    Transaction No
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>
                                    Payment Method
                                </th>

                                <th>
                                    Reference
                                </th>

                                <th>
                                    Created By
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($transactions as $transaction)

                                <tr>

                                    <td>

                                        {{ $transactions->firstItem() + $loop->index }}

                                    </td>


                                    <td>

                                        <strong>
                                            {{ $transaction->transaction_no }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{ $transaction->transaction_date?->format('d M Y') }}

                                    </td>


                                    <td>

                                        @if($transaction->transaction_type === 'deposit')

                                            <span class="badge bg-success">
                                                Deposit
                                            </span>

                                        @elseif($transaction->transaction_type === 'refund')

                                            <span class="badge bg-danger">
                                                Refund
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Adjustment
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        @if($transaction->transaction_type === 'refund')

                                            <span class="text-danger fw-semibold">

                                                -
                                                {{ number_format($transaction->amount, 2) }}

                                            </span>

                                        @else

                                            <span class="text-success fw-semibold">

                                                +
                                                {{ number_format($transaction->amount, 2) }}

                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($transaction->payment_method)

                                            {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        {{ $transaction->reference_no ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $transaction->createdBy?->name ?? 'System' }}

                                    </td>


                                    <td>

                                        {{ $transaction->remarks ?? '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-4">

                                        <i class="bx bx-info-circle fs-3 text-muted"></i>

                                        <div class="text-muted mt-2">

                                            No security money transactions found.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            @if($transactions->hasPages())

                <div class="card-footer">

                    {{ $transactions->links() }}

                </div>

            @endif

        </div>


    </div>

</div>

@endsection