@extends('Hr.setting.partial.layout')
@section('title','Tab Slabs')
@section('setting-content')

<div class="card">
    <h3>Slabs for {{ $setting->name }}</h3>
    <p style="color:var(--muted);margin-top:0">
        Income above the threshold is taxed slab by slab. Leave "To" empty for the top slab (no upper limit).
    </p>

    <form method="POST" action="{{ route('tax-slabs.store', $setting) }}" class="form-grid">
        @csrf
        <label>From amount <input type="number" step="any" min="0" name="from_amount" required></label>
        <label>To amount (blank = no limit) <input type="number" step="any" min="0" name="to_amount"></label>
        <label>Rate % <input type="number" step="any" min="0" name="rate" required></label>
        <div class="actions">
            <button type="submit" class="btn primary">Add slab</button>
            <a href="{{ route('tax-settings.index') }}" class="btn ghost">Back to tax settings</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th style="text-align:right">From</th><th style="text-align:right">To</th><th style="text-align:right">Rate %</th><th></th></tr></thead>
            <tbody>
                @forelse ($slabs as $slab)
                    <tr>
                        <td style="text-align:right">{{ number_format($slab->from_amount, 2) }}</td>
                        <td style="text-align:right">{{ $slab->to_amount !== null ? number_format($slab->to_amount, 2) : 'No limit' }}</td>
                        <td style="text-align:right">{{ (float) $slab->rate }}</td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('tax-slabs.destroy', $slab) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this slab?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">No slabs yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
