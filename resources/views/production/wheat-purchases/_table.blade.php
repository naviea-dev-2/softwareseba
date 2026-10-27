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

                @if($actions ?? false)
                    <th class="wheat-no-print">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse($purchases as $p)
                @php $supplierName = $p->supplier->name ?? $p->supplier_name; @endphp
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

                    <td class="wheat-right">{{ number_format($p->empty_weight_kg, 2) }}</td>
                    <td class="wheat-right">{{ number_format($p->loaded_weight_kg, 2) }}</td>
                    <td class="wheat-right">{{ number_format($p->net_weight_kg, 2) }}</td>

                    <td>{{ $supplierName ?: '—' }}</td>
                    <td>{{ $p->slip_receiver }}</td>

                    <td class="wheat-right">{{ number_format($p->rate_per_kg, 2) }}</td>
                    <td class="wheat-right">{{ number_format($p->purchase_amount, 2) }}</td>

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

                                {{-- Payments are supplier-wise: pay + history live on the supplier page --}}
                                @if($p->supplier_id)
                                    <a class="wheat-btn small success"
                                       href="{{ route('production.wheat-purchases.supplier-payments.show', $p->supplier_id) }}"
                                       title="Supplier Payments / Pay">
                                        <i class="bx bx-money"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="15">No wheat purchase records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>