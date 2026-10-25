<div class="wheat-table-wrap">
    <table class="wheat-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Weight ID</th>
                <th>Vehicle No</th>
                <th>Driver</th>
                <th>Mobile</th>
                <th>Quantity</th>
                <th>Unload KG</th>
                <th>Load KG</th>
                <th>Net KG</th>
                <th>Party Name</th>
                <th>Slip Receiver</th>
                <th>Price/KG</th>
                <th>Total Purchase</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>

                @if($actions ?? false)
                    <th class="wheat-no-print">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse($purchases as $p)
                @php
                    $paid = (float) ($p->paid_amount ?? 0);
                    $due  = (float) $p->purchase_amount - $paid;
                @endphp
                <tr>
                    <td>{{ $loop->iteration + ($offset ?? 0) }}</td>

                    <td>{{ $p->purchase_date?->format('d M Y') ?? '—' }}</td>

                    <td>{{ $p->weight_slip_no }}</td>
                    <td>{{ $p->vehicle_no }}</td>
                    <td>{{ $p->driver_name }}</td>
                    <td>{{ $p->driver_mobile }}</td>

                    <td>
                        {{ $p->quantity_description
                            ?: ($p->bag_quantity === null
                                ? '—'
                                : $p->bag_quantity . ' Bag') }}
                    </td>

                    <td class="wheat-right">{{ number_format($p->empty_weight_kg, 3) }}</td>
                    <td class="wheat-right">{{ number_format($p->loaded_weight_kg, 3) }}</td>
                    <td class="wheat-right">{{ number_format($p->net_weight_kg, 3) }}</td>

                    <td>{{ $p->supplier_name }}</td>
                    <td>{{ $p->slip_receiver }}</td>

                    <td class="wheat-right">{{ number_format($p->rate_per_kg, 4) }}</td>
                    <td class="wheat-right">{{ number_format($p->purchase_amount, 2) }}</td>

                    <td class="wheat-right">{{ number_format($paid, 2) }}</td>

                    <td class="wheat-right {{ $due > 0 ? 'wheat-text-danger' : 'wheat-text-success' }}">
                        {{ number_format($due, 2) }}
                    </td>

                    @if($actions ?? false)
                        <td class="wheat-no-print wheat-actions-cell">
                            <div class="wheat-actions">
                                <a class="wheat-btn small light"
                                   href="{{ route('production.wheat-purchases.show', $p->id) }}"
                                   title="View">
                                    <i class="bx bx-show"></i>
                                </a>

                                <a class="wheat-btn small light"
                                   href="{{ route('production.wheat-purchases.edit', $p->id) }}"
                                   title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form method="post"
                                      action="{{ route('production.wheat-purchases.destroy', $p->id) }}"
                                      onsubmit="return confirm('Delete this record?')"
                                      style="display:contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="wheat-btn small danger" title="Delete">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>

                                {{-- NEW: Payment History --}}
                                <button type="button"
                                        class="wheat-btn small info"
                                        title="Payment History"
                                        data-bs-toggle="modal"
                                        data-bs-target="#historyModal{{ $p->id }}">
                                    <i class="bx bx-history"></i>
                                </button>

                                @if($due > 0)
                                    <button type="button"
                                            class="wheat-btn small success"
                                            title="Pay Amount"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentModal{{ $p->id }}">
                                        <i class="bx bx-money"></i>
                                    </button>
                                @else
                                    <button type="button"
                                            class="wheat-btn small light"
                                            title="Fully Paid"
                                            disabled>
                                        <i class="bx bx-check-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="17">No wheat purchase records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ============================= --}}
{{-- HISTORY MODALS (one per row)  --}}
{{-- ============================= --}}
@if($actions ?? false)
    @foreach($purchases as $p)
        <div class="modal fade" id="historyModal{{ $p->id }}" tabindex="-1"
             aria-labelledby="historyModalLabel{{ $p->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyModalLabel{{ $p->id }}">
                            <i class="bx bx-history"></i>
                            Payment History — {{ $p->weight_slip_no }}
                            @if($p->supplier_name) ({{ $p->supplier_name }}) @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Summary strip --}}
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="p-2 border rounded">
                                    <small class="text-muted d-block">Total Purchase</small>
                                    <strong>{{ number_format($p->purchase_amount, 2) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-2 border rounded">
                                    <small class="text-muted d-block">Paid</small>
                                    <strong class="text-success">{{ number_format($p->paid_amount ?? 0, 2) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-2 border rounded">
                                    <small class="text-muted d-block">Due</small>
                                    @php $hDue = (float)$p->purchase_amount - (float)($p->paid_amount ?? 0); @endphp
                                    <strong class="{{ $hDue > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($hDue, 2) }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        @if($p->payments && $p->payments->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th class="text-end">Amount</th>
                                            <th>Method</th>
                                            <th>Bank / Reference</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p->payments->sortByDesc('created_at') as $i => $pay)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $pay->created_at?->format('d M Y, h:i A') }}</td>
                                                <td class="text-end">{{ number_format($pay->amount, 2) }}</td>
                                                <td>
                                                    @if($pay->method === 'cash')
                                                        <span class="badge bg-success">Cash</span>
                                                    @elseif($pay->method === 'bkash')
                                                        <span class="badge bg-danger">bKash</span>
                                                    @elseif($pay->method === 'bank')
                                                        <span class="badge bg-primary">Bank</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $pay->method }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pay->method === 'bank')
                                                        {{ $pay->bankAccount->acName ?? '—' }}
                                                        @if(!empty($pay->bankAccount->acNumber))
                                                            <small class="text-muted">({{ $pay->bankAccount->acNumber }})</small>
                                                        @endif
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>{{ $pay->note ?: '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total Paid</th>
                                            <th class="text-end">{{ number_format($p->payments->sum('amount'), 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bx bx-info-circle"></i> No payments recorded yet.
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        @php $fDue = (float)$p->purchase_amount - (float)($p->paid_amount ?? 0); @endphp
                        @if($fDue > 0)
                            <button type="button" class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal{{ $p->id }}"
                                    data-bs-dismiss="modal">
                                <i class="bx bx-money"></i> Pay Now
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

{{-- ============================= --}}
{{-- PAYMENT MODALS (one per row)  --}}
{{-- ============================= --}}
@if($actions ?? false)
    @foreach($purchases as $p)
        @php
            $paid = (float) ($p->paid_amount ?? 0);
            $due  = (float) $p->purchase_amount - $paid;
        @endphp

        @if($due > 0)
            <div class="modal fade" id="paymentModal{{ $p->id }}" tabindex="-1"
                 aria-labelledby="paymentModalLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('production.wheat-purchases.pay', $p->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel{{ $p->id }}">
                                    <i class="bx bx-money"></i>
                                    Pay Amount — {{ $p->weight_slip_no }} ({{ $p->supplier_name }})
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-2">
                                    <strong>Total Purchase:</strong>
                                    {{ number_format($p->purchase_amount, 2) }}
                                </div>
                                <div class="mb-2">
                                    <strong>Already Paid:</strong>
                                    {{ number_format($paid, 2) }}
                                </div>
                                <div class="mb-3">
                                    <strong>Due:</strong>
                                    <span class="{{ $due > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($due, 2) }}
                                    </span>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label for="amount{{ $p->id }}" class="form-label">Payment Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           min="0.01"
                                           max="{{ $due }}"
                                           class="form-control"
                                           id="amount{{ $p->id }}"
                                           name="amount"
                                           required
                                           value="{{ $due }}">
                                </div>

                                <div class="mb-3">
                                    <label for="method{{ $p->id }}" class="form-label">Payment Method</label>
                                    <select class="form-select"
                                            id="method{{ $p->id }}"
                                            name="method"
                                            required
                                            onchange="toggleBankAccount{{ $p->id }}(this.value)">
                                        <option value="cash">Cash</option>
                                        <option value="bkash">bKash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="bankAccountGroup{{ $p->id }}" style="display:none;">
                                    <label for="bank_account{{ $p->id }}" class="form-label">
                                        <i class="bx bx-building-house"></i> Bank Account
                                    </label>
                                    <select class="form-select"
                                            id="bank_account{{ $p->id }}"
                                            name="bank_account_id">
                                        <option value="">— Select Bank Account —</option>
                                        @foreach($bankAccounts ?? [] as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->acName }} ({{ $account->acNumber }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="note{{ $p->id }}" class="form-label">Note (Optional)</label>
                                    <textarea class="form-control"
                                              id="note{{ $p->id }}"
                                              name="note"
                                              rows="2"></textarea>
                                </div>

                                {{-- Quick payment history preview --}}
                                @if($p->payments && $p->payments->count())
                                    <hr>
                                    <small class="text-muted d-block mb-1">
                                        Recent payments ({{ $p->payments->count() }} total)
                                    </small>
                                    <ul class="list-unstyled small mb-0">
                                        @foreach($p->payments->sortByDesc('created_at')->take(3) as $pay)
                                            <li class="d-flex justify-content-between border-bottom py-1">
                                                <span>
                                                    {{ $pay->created_at?->format('d M Y') }}
                                                    — {{ ucfirst($pay->method) }}
                                                </span>
                                                <strong>{{ number_format($pay->amount, 2) }}</strong>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-check"></i> Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function toggleBankAccount{{ $p->id }}(method) {
                    var bankGroup  = document.getElementById('bankAccountGroup{{ $p->id }}');
                    var bankSelect = document.getElementById('bank_account{{ $p->id }}');
                    if (method === 'bank') {
                        bankGroup.style.display = 'block';
                        bankSelect.required = true;
                    } else {
                        bankGroup.style.display = 'none';
                        bankSelect.required = false;
                        bankSelect.value = '';
                    }
                }
            </script>
        @endif
    @endforeach
@endif
{{-- Bootstrap 5 JS bundle (only load if not already loaded globally) --}}
@once
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endonce