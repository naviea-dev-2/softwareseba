<style>
/* ============ LAYOUT SHELL ============ */
.wheat-wrap {
    max-width: 1500px;
    margin: 0 auto;
    padding: 18px 16px 40px;
    font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
    color: #1f2937;
}

.wheat-muted {
    color: #6b7280;
    font-size: .85rem;
}

/* ============ PAGE HEADER ============ */
.wheat-pagehead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 18px 22px;
    margin-bottom: 16px;
    background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
    border-radius: 14px;
    color: #fff;
    box-shadow: 0 10px 24px -12px rgba(4,120,87,.55);
    flex-wrap: wrap;
}
.wheat-pagehead-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.wheat-pagehead-icon {
    width: 48px; height: 48px;
    display: grid; place-items: center;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    font-size: 1.6rem;
    color: #fef3c7;
}
.wheat-pagehead h2 {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: .2px;
}
.wheat-pagehead .wheat-muted {
    color: #d1fae5;
    font-size: .82rem;
}
.wheat-pagehead-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ============ BUTTONS ============ */
.wheat-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 9px;
    font-size: .88rem;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: .15s ease;
    line-height: 1;
}
.wheat-btn i { font-size: 1.05rem; }

.wheat-btn.primary {
    background: #059669;
    color: #fff;
}
.wheat-btn.primary:hover {
    background: #047857;
    transform: translateY(-1px);
    box-shadow: 0 6px 12px -6px rgba(5,150,105,.6);
}
.wheat-btn.light {
    background: #ffffff;
    color: #065f46;
    border-color: #d1fae5;
}
.wheat-btn.light:hover {
    background: #ecfdf5;
    border-color: #a7f3d0;
}
.wheat-pagehead .wheat-btn.light {
    background: rgba(255,255,255,.16);
    color: #fff;
    border-color: rgba(255,255,255,.35);
    backdrop-filter: blur(4px);
}
.wheat-pagehead .wheat-btn.light:hover {
    background: rgba(255,255,255,.28);
}

/* ============ KPI CARDS ============ */
.wheat-kpis {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 14px;
    margin-bottom: 16px;
}
.wheat-kpi {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px -4px rgba(0,0,0,.08);
    transition: .15s ease;
}
.wheat-kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px -12px rgba(0,0,0,.25);
}
.wheat-kpi-icon {
    width: 46px; height: 46px;
    display: grid; place-items: center;
    border-radius: 10px;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.wheat-kpi-body {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.wheat-kpi-body b {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.15;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* KPI color variants */
.wheat-kpi-deliveries .wheat-kpi-icon { background: #eff6ff; color: #2563eb; }
.wheat-kpi-kg         .wheat-kpi-icon { background: #ecfeff; color: #0891b2; }
.wheat-kpi-ton        .wheat-kpi-icon { background: #f0fdf4; color: #16a34a; }
.wheat-kpi-purchase   .wheat-kpi-icon { background: #fef3c7; color: #d97706; }
.wheat-kpi-paid       .wheat-kpi-icon { background: #dcfce7; color: #15803d; }
.wheat-kpi-due        .wheat-kpi-icon { background: #fee2e2; color: #b91c1c; }

.wheat-kpi-paid .wheat-kpi-body b   { color: #15803d; }
.wheat-kpi-due  .wheat-kpi-body b   { color: #b91c1c; }

/* ============ CARDS ============ */
.wheat-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px -6px rgba(0,0,0,.10);
    overflow: hidden;
}
.wheat-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
}
.wheat-card-head h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.wheat-card-head h4 i {
    color: #059669;
    font-size: 1.15rem;
}
.wheat-card-body {
    padding: 16px 20px;
}
.wheat-flex-between {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.wheat-card-filter .wheat-card-body {
    padding-bottom: 8px;
}

/* ============ FILTERED TOTALS STRIP ============ */
.wheat-filter-totals {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    padding: 12px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}
.wheat-ft-item {
    flex: 1;
    min-width: 180px;
    display: flex;
    flex-direction: column;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff;
}
.wheat-ft-item .wheat-muted {
    font-size: .78rem;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 2px;
}
.wheat-ft-item b {
    font-size: 1.05rem;
    font-weight: 700;
}
.wheat-ft-purchase { border-left: 4px solid #d97706; }
.wheat-ft-purchase b { color: #b45309; }

.wheat-ft-paid { border-left: 4px solid #16a34a; }
.wheat-ft-paid b { color: #15803d; }

.wheat-ft-due { border-left: 4px solid #dc2626; }
.wheat-ft-due b { color: #b91c1c; }

/* ============ TABLE BODY ============ */
.wheat-table-body {
    padding: 0 !important;
}
.wheat-table-wrap {
    overflow-x: auto;
}
.wheat-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: .88rem;
}
.wheat-table thead th {
    background: #f1f5f9;
    color: #334155;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .03em;
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
    text-align: left;
}
.wheat-table tbody td {
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    color: #1f2937;
    vertical-align: middle;
}
.wheat-table tbody tr:hover {
    background: #f8fafc;
}
.wheat-right { text-align: right; }
.wheat-no-print { text-align: center; }

/* ============ PAGINATION ============ */
.wheat-pagination {
    padding: 14px 20px;
    border-top: 1px solid #f1f5f9;
    background: #fafbff;
}

/* small text helpers */
.text-danger  { color: #dc2626 !important; }
.text-success { color: #16a34a !important; }

    .wheat-text-danger  { color: #dc3545; }
    .wheat-text-success { color: #28a745; }

    .wheat-actions-cell {
        white-space: nowrap;
        width: 1%;
    }
    .wheat-actions {
        display: inline-flex;
        flex-wrap: nowrap;
        gap: 4px;
        align-items: center;
    }
    .wheat-actions form { margin: 0; }
    .wheat-actions .wheat-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 7px;
        line-height: 1;
    }
    .wheat-btn i        { font-size: 1.1em; vertical-align: middle; }
    .wheat-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .wheat-btn.info     { background: #0dcaf0; color: #000; border: 1px solid #0dcaf0; }
    .wheat-btn.info:hover { background: #0bb7d8; }
/* ============ REPORT PAGE ============ */
.wheat-print-title {
    display: none;              /* hidden on screen, shown on print */
    text-align: center;
    margin-bottom: 18px;
}
.wheat-print-title h2 {
    margin: 0 0 2px;
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
}
.wheat-print-title h3 {
    margin: 0 0 2px;
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
}
.wheat-print-title div {
    font-size: .9rem;
    color: #475569;
}
.wheat-print-title small {
    display: block;
    margin-top: 4px;
    font-size: .78rem;
    color: #64748b;
}

/* Report table header alignment */
.wheat-table thead th.wheat-right {
    text-align: right;
}

/* Ensure summary KPI grid behaves on report page too */
.wheat-kpis {
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
}

/* ============ PRINT STYLES ============ */
@media print {
    body {
        background: #fff !important;
    }
    .wheat-no-print,
    nav,
    header,
    footer,
    .sidebar,
    .breadcrumb,
    .wheat-pagehead,
    .wheat-card-filter,
    .wheat-pagination {
        display: none !important;
    }
    .wheat-wrap {
        max-width: none;
        padding: 0;
        margin: 0;
    }
    .wheat-card {
        box-shadow: none;
        border: 1px solid #cbd5e1;
        page-break-inside: avoid;
        margin-bottom: 14px;
        border-radius: 0;
    }
    .wheat-card-head {
        background: #f1f5f9 !important;
        border-bottom: 1px solid #cbd5e1;
    }
    .wheat-table {
        font-size: 11px;
    }
    .wheat-table thead th {
        background: #e2e8f0 !important;
        color: #000 !important;
    }
    .wheat-table tbody td {
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Show print-only title */
    .wheat-print-title {
        display: block !important;
        page-break-after: avoid;
    }

    /* KPI cards on print */
    .wheat-kpis {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 14px;
    }
    .wheat-kpi {
        padding: 10px 12px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    .wheat-kpi-icon {
        width: 34px !important;
        height: 34px !important;
        font-size: 1rem !important;
    }
    .wheat-kpi-body b {
        font-size: 1rem !important;
    }

    /* Ensure table fits the page */
    .wheat-table-wrap {
        overflow: visible !important;
    }
    .wheat-table {
        width: 100% !important;
        table-layout: auto !important;
    }
    .wheat-table thead {
        display: table-header-group; /* repeat headers on each page */
    }
    .wheat-table tr {
        page-break-inside: avoid;
    }
}
</style>