<style>
    /* ============================================================
   WHEAT MODULE STYLES (merged)
   ============================================================ */

    /* ============ LAYOUT SHELL ============ */
    .wheat-wrap {
        max-width: 1450px;
        margin: 22px auto;
        padding: 0 15px 40px;
        color: #243247;
        font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
    }

    .wheat-muted {
        color: #718096;
        font-size: 13px;
    }

    /* ============ PAGE HEADER ============ */
    .wheat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .wheat-top h2 {
        font-size: 25px;
        font-weight: 750;
        margin: 0;
    }

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
        box-shadow: 0 10px 24px -12px rgba(4, 120, 87, .55);
        flex-wrap: wrap;
    }

    .wheat-pagehead-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .wheat-pagehead-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, .18);
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
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        border: 0;
        border-radius: 8px;
        background: #234c83;
        color: #fff !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: .15s ease;
        line-height: 1;
    }

    .wheat-btn i {
        font-size: 1.05rem;
        vertical-align: middle;
    }

    .wheat-btn:hover {
        filter: brightness(.95);
    }

    .wheat-btn.primary {
        background: #059669;
        color: #fff !important;
    }

    .wheat-btn.primary:hover {
        background: #047857;
        filter: none;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px -6px rgba(5, 150, 105, .6);
    }

    .wheat-btn.light {
        background: #eff3f8;
        color: #234c83 !important;
    }

    .wheat-btn.light:hover {
        background: #e3eaf3;
        filter: none;
    }

    .wheat-btn.danger {
        background: #fff1f2;
        color: #be123c !important;
    }

    .wheat-btn.danger:hover {
        background: #ffe4e6;
        filter: none;
    }

    .wheat-btn.info {
        background: #0dcaf0;
        color: #000 !important;
        border: 1px solid #0dcaf0;
    }

    .wheat-btn.info:hover {
        background: #0bb7d8;
        filter: none;
    }

    .wheat-btn.small {
        padding: 6px 9px;
        font-size: 12px;
    }

    .wheat-btn:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    /* Header button overrides */
    .wheat-pagehead .wheat-btn.light {
        background: rgba(255, 255, 255, .16);
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, .35);
        backdrop-filter: blur(4px);
    }

    .wheat-pagehead .wheat-btn.light:hover {
        background: rgba(255, 255, 255, .28);
    }


    /* ============ KPI CARDS ============ */
    .wheat-kpis {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .wheat-kpi {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        min-width: 0;
        background: #fff;
        border: 1px solid #e4e9f0;
        border-radius: 12px;
        box-shadow: 0 2px 6px -4px rgba(0, 0, 0, .08);
        transition: .15s ease;
    }

    .wheat-kpi-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }

    .wheat-kpi-body b {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin-top: 2px;
        white-space: normal;
        overflow: visible;
        overflow-wrap: anywhere;
        text-overflow: clip;
        font-variant-numeric: tabular-nums;
    }

    /* Responsive layout */
    @media (max-width: 850px) {
        .wheat-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {
        .wheat-kpis {
            grid-template-columns: 1fr;
        }
    }



    /* Fallback for plain text KPIs */
    .wheat-kpi>b {
        display: block;
        font-size: 22px;
        margin-top: 6px;
    }

    /* KPI color variants */
    .wheat-kpi-deliveries .wheat-kpi-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .wheat-kpi-kg .wheat-kpi-icon {
        background: #ecfeff;
        color: #0891b2;
    }

    .wheat-kpi-ton .wheat-kpi-icon {
        background: #f0fdf4;
        color: #16a34a;
    }

    .wheat-kpi-purchase .wheat-kpi-icon {
        background: #fef3c7;
        color: #d97706;
    }

    .wheat-kpi-paid .wheat-kpi-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .wheat-kpi-due .wheat-kpi-icon {
        background: #fee2e2;
        color: #b91c1c;
    }

    .wheat-kpi-paid .wheat-kpi-body b {
        color: #15803d;
    }

    .wheat-kpi-due .wheat-kpi-body b {
        color: #b91c1c;
    }

    /* ============ CARDS ============ */
    .wheat-card {
        background: #fff;
        border: 1px solid #e4e9f0;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(29, 43, 68, .045);
        padding: 22px;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .wheat-card h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 18px;
    }

    .wheat-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        margin: -22px -22px 18px;
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
        padding: 0;
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

    /* ============ FORM FIELDS ============ */
    .wheat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .wheat-field label {
        font-weight: 650;
        font-size: 13px;
        display: block;
        margin-bottom: 7px;
    }

    .wheat-field input,
    .wheat-field select,
    .wheat-field textarea {
        display: block;
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #dce3eb;
        border-radius: 9px;
        background: #fff;
        color: #243247;
        box-sizing: border-box;
        transition: .15s;
    }

    .wheat-field input:focus,
    .wheat-field select:focus,
    .wheat-field textarea:focus {
        outline: none;
        border-color: #4384f4;
        box-shadow: 0 0 0 3px rgba(36, 101, 238, .1);
    }

    .wheat-field textarea {
        min-height: 90px;
    }

    .wheat-filter {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .wheat-filter .wheat-field input,
    .wheat-filter .wheat-field select {
        min-height: 40px;
    }

    .wheat-full {
        grid-column: 1 / -1;
    }

    /* ============ ACTIONS ROW ============ */
    .wheat-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .wheat-actions-cell {
        white-space: nowrap;
        width: 1%;
    }

    .wheat-table .wheat-actions {
        display: inline-flex;
        flex-wrap: nowrap;
        gap: 4px;
        align-items: center;
    }

    .wheat-actions form {
        margin: 0;
    }

    .wheat-table .wheat-actions .wheat-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 7px;
        line-height: 1;
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

    .wheat-ft-purchase {
        border-left: 4px solid #d97706;
    }

    .wheat-ft-purchase b {
        color: #b45309;
    }

    .wheat-ft-paid {
        border-left: 4px solid #16a34a;
    }

    .wheat-ft-paid b {
        color: #15803d;
    }

    .wheat-ft-due {
        border-left: 4px solid #dc2626;
    }

    .wheat-ft-due b {
        color: #b91c1c;
    }

    /* ============ TABLES ============ */
    body {
    padding-bottom: 20px;
}
    .wheat-table-wrap {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        position: relative;
    }

    /* Keep the table wide enough for all columns */
    .wheat-table {
        min-width: 1800px;
        /* Adjust if needed */
        white-space: nowrap;
        margin-bottom: 0;
    }

    /* Floating horizontal scrollbar */
    .wheat-fixed-scroll {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 18px;
        overflow-x: auto;
        overflow-y: hidden;
        z-index: 1050;
        background: #f1f5f9;
        border-top: 1px solid #cbd5e1;
    }

    /* Invisible content creates the matching scroll width */
    .wheat-fixed-scroll-inner {
        height: 1px;
    }

    .wheat-table th,
    .wheat-table thead th {
        background: #243a57;
        color: #fff;
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
        border-bottom: 1px solid #1f3149;
    }

    .wheat-table td,
    .wheat-table tbody td {
        padding: 11px 10px;
        border-bottom: 1px solid #edf1f5;
        color: #243247;
        vertical-align: middle;
    }

    .wheat-table tbody tr:hover {
        background: #f7faff;
    }

    .wheat-table tbody tr:nth-child(odd) {
        background: #f8fbff;
    }

    .wheat-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .wheat-table thead th.wheat-right {
        text-align: right;
    }

    .wheat-table-body {
        padding: 0 !important;
    }

    .wheat-right {
        text-align: right;
    }

    .wheat-no-print {
        text-align: center;
    }

    /* ============ ALERTS ============ */
    .wheat-alert {
        padding: 12px 15px;
        border-radius: 9px;
        margin-bottom: 15px;
        background: #e9f8ef;
        color: #17613c;
    }

    .wheat-invalid {
        background: #fff4e8;
        color: #9b4a00;
    }

    .wheat-error {
        font-size: 12px;
        color: #c53030;
        margin-top: 5px;
    }

    /* ============ PAGINATION ============ */
    .wheat-pagination {
        padding: 14px 20px;
        border-top: 1px solid #f1f5f9;
        background: #fafbff;
    }

    /* ============ PRINT TITLE (hidden on screen) ============ */
    .wheat-print-title {
        display: none;
        text-align: center;
        margin: 0 0 20px;
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

    /* ============ TEXT HELPERS ============ */
    .text-danger,
    .wheat-text-danger {
        color: #dc2626 !important;
    }

    .text-success,
    .wheat-text-success {
        color: #16a34a !important;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1200px) {
        .wheat-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 850px) {

        .wheat-kpis,
        .wheat-filter,
        .wheat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {

        .wheat-grid,
        .wheat-kpis,
        .wheat-filter {
            grid-template-columns: 1fr;
        }

        .wheat-wrap {
            padding: 0 10px 30px;
        }

        .wheat-top h2 {
            font-size: 22px;
        }
    }

    /* ============ PRINT ============ */
    @media print {
        body {
            background: #fff !important;
        }

        .main-menu,
        .header-navbar,
        .navbar,
        .wheat-no-print,
        .wheat-pagehead,
        .wheat-card-filter,
        .wheat-pagination,
        aside,
        nav,
        header,
        footer,
        .sidebar,
        .breadcrumb {
            display: none !important;
        }

        .wheat-wrap {
            max-width: none;
            margin: 0;
            padding: 0;
        }

        .wheat-card {
            border: 1px solid #cbd5e1;
            box-shadow: none;
            padding: 0;
            margin-bottom: 14px;
            border-radius: 0;
            page-break-inside: avoid;
        }

        .wheat-card-head {
            background: #f1f5f9 !important;
            border-bottom: 1px solid #cbd5e1;
            margin: 0;
            padding: 8px 12px;
        }

        .wheat-table {
            font-size: 10px;
            width: 100% !important;
            table-layout: auto !important;
        }

        .wheat-table th,
        .wheat-table td {
            padding: 5px;
            border: 1px solid #ccc !important;
        }

        .wheat-table th,
        .wheat-table thead th {
            background: #e2e8f0 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .wheat-table tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .wheat-table thead {
            display: table-header-group;
        }

        .wheat-table tr {
            page-break-inside: avoid;
        }

        .wheat-table-wrap {
            overflow: visible !important;
        }

        /* Print title */
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

        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    }



    /* ============ PROFESSIONAL PAGINATION ============ */

    .wheat-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding: 16px 20px;
        margin-top: 0;
        background: #fff;
        border-top: 1px solid #e5eaf0;
        border-radius: 0 0 12px 12px;
    }

    /* Laravel pagination wrapper */
    .wheat-pagination nav {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .wheat-pagination nav>div {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    /* Laravel Bootstrap pagination */
    .wheat-pagination .pagination {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .wheat-pagination .page-item {
        margin: 0;
    }

    .wheat-pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 11px;
        border: 1px solid #dce4ed;
        border-radius: 8px !important;
        background: #fff;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: none;
        transition: all .18s ease;
    }

    .wheat-pagination .page-link:hover {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
        z-index: 1;
    }

    .wheat-pagination .page-item.active .page-link {
        background: #059669;
        color: #fff;
        border-color: #059669;
        box-shadow: 0 3px 8px rgba(5, 150, 105, .2);
    }

    .wheat-pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #a0aec0;
        border-color: #e8edf3;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Laravel pagination information */
    .wheat-pagination nav p,
    .wheat-pagination nav>div:first-child {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Bootstrap pagination summary */
    .wheat-pagination .small {
        color: #64748b;
        font-size: 13px;
    }

    /* SVG arrows */
    .wheat-pagination svg {
        width: 16px;
        height: 16px;
    }

    /* Mobile */
    @media (max-width: 560px) {
        .wheat-pagination {
            flex-direction: column;
            align-items: center;
            padding: 14px 10px;
            gap: 12px;
        }

        .wheat-pagination nav {
            justify-content: center;
            width: 100%;
        }

        .wheat-pagination .pagination {
            justify-content: center;
            gap: 4px;
        }

        .wheat-pagination .page-link {
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            font-size: 12px;
        }
    }


</style>