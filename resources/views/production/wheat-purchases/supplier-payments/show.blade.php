@extends('inc.master')

@section('title', 'Supplier Payment - ' . $supplier->name)

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ $supplier->name }}</h4>
            <p class="text-muted mb-0">Purchases, payments and outstanding balance for this supplier.</p>
        </div>
        <a href="{{ route('production.wheat-purchases.supplier-payments.index') }}"
           class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back"></i> Back to Suppliers
        </a>
    </div>

    {{-- Flash / validation messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Deliveries</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['deliveries']) }}</div>
                    <div class="text-muted small">{{ number_format($summary['total_kg'], 2) }} KG</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total Purchases</div>
                    <div class="fs-4 fw-bold">৳ {{ number_format($summary['amount'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total Paid</div>
                    <div class="fs-4 fw-bold text-success">৳ {{ number_format($summary['paid'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Outstanding</div>
                    <div class="fs-4 fw-bold {{ $summary['due'] > 0 ? 'text-danger' : 'text-success' }}">
                        ৳ {{ number_format($summary['due'], 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- LEFT: purchases + history --}}
        <div class="col-lg-8">

            {{-- Date filter --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <form method="get"
                          action="{{ route('production.wheat-purchases.supplier-payments.show', $supplier->id) }}"
                          class="row g-2 align-items-end">
                        <div class="col-sm-4">
                            <label class="form-label small text-muted mb-1">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small text-muted mb-1">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                        </div>
                        <div class="col-sm-4 d-flex gap-2">
                            <button class="btn btn-primary" type="submit"><i class="bx bx-search"></i> Filter</button>
                            <a href="{{ route('production.wheat-purchases.supplier-payments.show', $supplier->id) }}"
                               class="btn btn-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Purchases --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Purchases</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Slip No</th>
                                    <th>Vehicle</th>
                                    <th class="text-end">Net KG</th>
                                    <th class="text-end">Rate/KG</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->purchase_date?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('production.wheat-purchases.show', $purchase->id) }}">
                                                {{ $purchase->weight_slip_no }}
                                            </a>
                                        </td>
                                        <td>{{ $purchase->vehicle_no ?: '-' }}</td>
                                        <td class="text-end">{{ number_format($purchase->net_weight_kg, 2) }}</td>
                                        <td class="text-end">{{ number_format($purchase->rate_per_kg, 2) }}</td>
                                        <td class="text-end">{{ number_format($purchase->purchase_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No purchases found for this supplier.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="small text-muted mt-2">
                        The cards above show this supplier's all-time totals. The date filter only narrows this list.
                    </div>

                    <div class="mt-3">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>

            {{-- Payment history --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Payments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Bank Account</th>
                                    <th>Note</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    @php
                                        $bank = $payment->bank_account_id
                                            ? $bankAccounts->firstWhere('id', $payment->bank_account_id)
                                            : null;
                                    @endphp
                                    <tr>
                                        <td>{{ $payment->created_at?->format('d M Y h:i A') }}</td>
                                        <td class="text-capitalize">{{ $payment->method }}</td>
                                        <td>
                                            @if($bank)
                                                {{ $bank->account_name }}
                                                @if($bank->account_number)
                                                    <small class="text-muted">({{ $bank->account_number }})</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $payment->note ?: '-' }}</td>
                                        <td class="text-end text-success fw-bold">
                                            {{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No payments recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: pay form --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="position:sticky;top:15px">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bx bx-wallet"></i> Make Payment</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border mb-3">
                        Outstanding:
                        <strong class="{{ $summary['due'] > 0 ? 'text-danger' : 'text-success' }}">
                            ৳ {{ number_format($summary['due'], 2) }}
                        </strong>
                        <div class="small text-muted mt-1">
                            Outstanding = total purchases minus all payments made to this supplier.
                        </div>
                    </div>

                    @if($summary['due'] > 0)
                        <form method="post"
                              action="{{ route('production.wheat-purchases.supplier-payments.pay', $supplier->id) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" max="{{ $summary['due'] }}"
                                       name="amount" value="{{ old('amount') }}"
                                       class="form-control @error('amount') is-invalid @enderror" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <button type="button" class="btn btn-link btn-sm px-0"
                                        onclick="document.querySelector('[name=amount]').value='{{ number_format($summary['due'], 2, '.', '') }}'">
                                    Pay full outstanding
                                </button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Method <span class="text-danger">*</span></label>
                                <select name="method" id="pay_method"
                                        class="form-select @error('method') is-invalid @enderror" required>
                                    <option value="cash" @selected(old('method', 'cash') === 'cash')>Cash</option>
                                    <option value="bkash" @selected(old('method') === 'bkash')>bKash</option>
                                    <option value="bank" @selected(old('method') === 'bank')>Bank</option>
                                </select>
                                @error('method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="bank_wrap" style="display:none">
                                <label class="form-label">Bank Account <span class="text-danger">*</span></label>
                                <select name="bank_account_id" id="bank_account_id"
                                        class="form-select @error('bank_account_id') is-invalid @enderror">
                                    <option value="">Select account</option>
                                    @foreach($bankAccounts as $bank)
                                        <option value="{{ $bank->id }}"
                                            @selected((string) old('bank_account_id') === (string) $bank->id)>
                                            {{ $bank->account_name }}
                                            @if($bank->account_number) ({{ $bank->account_number }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" rows="2" maxlength="500"
                                          class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Record this payment for {{ addslashes($supplier->name) }}?')">
                                <i class="bx bx-check"></i> Record Payment
                            </button>
                        </form>
                    @else
                        <div class="text-center text-success py-3">
                            <i class="bx bx-check-circle fs-1"></i>
                            <div>All dues are cleared.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const method = document.getElementById('pay_method');
        const wrap = document.getElementById('bank_wrap');
        const bank = document.getElementById('bank_account_id');
        if (!method || !wrap || !bank) return;

        function toggle() {
            const isBank = method.value === 'bank';
            wrap.style.display = isBank ? '' : 'none';
            bank.required = isBank;
            if (!isBank) bank.value = '';
        }

        method.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endsection