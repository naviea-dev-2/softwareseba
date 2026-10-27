@extends('inc.master')

@section('head')
    <link href="{{ asset('public/assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/sweetalert2.min.css') }}" rel="stylesheet">
    
    <title>@yield('title')</title> 
    <style>
        :root{--bg:#f5f7fb;--surface:#fff;--text:#1f2937;--muted:#6b7280;--border:#e5e7eb;--primary:#2563eb;--danger:#dc2626;--radius:8px;color-scheme:light}
        @media (prefers-color-scheme: dark){:root{--bg:#0f172a;--surface:#1e293b;--text:#e2e8f0;--muted:#94a3b8;--border:#334155;--primary:#3b82f6;--danger:#f87171;color-scheme:dark}}
        *{box-sizing:border-box}
        body{margin:0;font:14px/1.45 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;background:var(--bg);color:var(--text)}
        header{padding:14px 20px;font-size:18px;font-weight:700;background:linear-gradient(90deg,var(--primary),#7c3aed);color:#fff;position:sticky;top:0;z-index:5;box-shadow:0 2px 8px rgba(0,0,0,.12)}
        .tabs{display:flex;gap:4px;overflow-x:auto;padding:8px 12px;background:var(--surface);border-bottom:1px solid var(--border)}
        .tabs a{color:var(--muted);text-decoration:none;padding:8px 12px;border-radius:6px;white-space:nowrap}
        .tabs a:hover{background:var(--bg);color:var(--text)}
        .tabs a.on{background:var(--primary);color:#fff}
        main{padding:16px;max-width:1200px;margin:0 auto}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .card h3{margin:0 0 12px;font-size:15px}
        .form-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px}
        label{display:flex;flex-direction:column;gap:4px;font-size:12px;color:var(--muted)}
        input,select{font:inherit;color:var(--text);background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:8px;min-width:0;width:100%}
        input:focus,select:focus{outline:2px solid var(--primary);outline-offset:1px}
        .toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:14px;padding:10px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius)}
        .toolbar input[type=search]{flex:1;min-width:220px;width:auto}
        .toolbar select,.toolbar input{width:auto;min-width:140px}
        .toolbar label{flex-direction:row;align-items:center;gap:6px}
        .count{color:var(--muted);font-size:12px;margin-left:auto}
        .btn{font:inherit;border:0;border-radius:6px;padding:8px 14px;cursor:pointer;text-decoration:none;display:inline-block;white-space:nowrap}
        .btn:hover{opacity:.9}
        .primary{background:var(--primary);color:#fff}
        .ghost{background:transparent;color:var(--text);border:1px solid var(--border)}
        .actions{grid-column:1/-1;display:flex;gap:8px}
        .table-wrap{overflow:auto;max-height:560px;border:1px solid var(--border);border-radius:var(--radius)}
        table{width:100%;border-collapse:collapse}
        th,td{text-align:left;padding:11px 12px;border-bottom:1px solid var(--border);white-space:nowrap}
        thead th{position:sticky;top:0;z-index:1;background:var(--bg);font-size:12px;text-transform:uppercase;letter-spacing:.4px;color:var(--muted)}
        tbody tr:nth-child(even){background:rgba(127,127,127,.04)}
        tbody tr:hover{background:var(--bg)}
        .badge{padding:2px 9px;border-radius:99px;background:rgba(37,99,235,.12);color:var(--primary);font-size:12px;font-weight:600}
        .link{background:none;border:0;color:var(--primary);cursor:pointer;font:inherit;padding:0 4px;text-decoration:none}
        .danger{color:var(--danger)}
        .pager{margin-top:12px}
        .empty{text-align:center;color:var(--muted);padding:20px}
        .alert{padding:10px 12px;border-radius:var(--radius);background:rgba(37,99,235,.1);color:var(--primary);margin-bottom:12px}
        .alert.error{background:rgba(220,38,38,.1);color:var(--danger)}
        .modal{position:fixed;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;padding:16px;z-index:50}
        .modal-box{background:var(--surface);border-radius:var(--radius);width:100%;max-width:720px;max-height:90vh;overflow:auto;padding:16px;box-shadow:0 10px 30px rgba(0,0,0,.25)}
    </style>
    @yield('style')
@endsection

@section('content')
@include('Hr.salary_section.partial.tabs')
@include('Hr.salary_section.partial.flash')
@yield('setting-content')
@endsection