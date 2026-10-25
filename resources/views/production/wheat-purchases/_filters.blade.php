<form class="wheat-filter wheat-no-print" method="get" action="{{ $action }}">
    <div class="wheat-field">
        <label>From Date</label>
        <input type="date" name="from" value="{{ request('from') }}">
    </div>

    <div class="wheat-field">
        <label>To Date</label>
        <input type="date" name="to" value="{{ request('to') }}">
    </div>

    <div class="wheat-field">
        <label>Supplier</label>
        <input name="supplier" placeholder="Supplier name" value="{{ request('supplier') }}">
    </div>

    <div class="wheat-field">
        <label>Slip / Vehicle / Driver</label>
        <input name="search" placeholder="Search" value="{{ request('search') }}">
    </div>

    <div class="wheat-actions wheat-full">
        <button type="submit" class="wheat-btn primary">
            <i class="bx bx-search"></i> Apply Filters
        </button>
        <a href="{{ $action }}" class="wheat-btn light">
            <i class="bx bx-reset"></i> Reset
        </a>
    </div>
</form>
<style>
/* ============ FILTER FORM ============ */
.wheat-card-filter {
    overflow: visible;
}

.wheat-filter {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px 16px;
    margin: 0;
}

.wheat-field {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.wheat-field label {
    font-size: .74rem;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .03em;
}

.wheat-field input {
    height: 40px;
    padding: 0 12px;
    border: 1px solid #d1d5db;
    border-radius: 9px;
    background: #fff;
    color: #0f172a;
    font-size: .9rem;
    transition: .15s;
    box-sizing: border-box;
    width: 100%;
}

.wheat-field input::placeholder {
    color: #94a3b8;
}

.wheat-field input:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, .15);
}

/* Action row – full width, right-aligned */
.wheat-filter .wheat-full {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 4px;
    border-top: 1px dashed #e2e8f0;
    margin-top: 4px;
    padding-top: 14px;
}

.wheat-filter .wheat-actions .wheat-btn {
    height: 40px;
    padding: 0 18px;
    font-size: .9rem;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Responsive breakpoints */
@media (max-width: 1100px) {
    .wheat-filter {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .wheat-filter {
        grid-template-columns: 1fr;
    }
    .wheat-filter .wheat-full {
        justify-content: stretch;
        flex-direction: column;
    }
    .wheat-filter .wheat-actions .wheat-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>