@extends('inc.master')
@section('content')
<style>
.qr-wrap{max-width:1450px;margin:auto;padding:25px 18px 50px;color:#25364b}.qr-header,.qr-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}.qr-header{margin-bottom:23px}.qr-eyebrow{font-size:11px;font-weight:800;letter-spacing:1.5px;color:#2563eb;text-transform:uppercase}.qr-header h2{font-size:27px;font-weight:800;margin:4px 0}.qr-muted{color:#7b8b9f;font-size:13px}.qr-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:1px solid transparent;border-radius:10px;padding:11px 16px;font-size:13px;font-weight:700;text-decoration:none!important;cursor:pointer;background:#2563eb;color:#fff!important}.qr-btn:hover{background:#1d4ed8;color:#fff!important}.qr-btn.secondary{background:#f1f5fa;color:#334155!important;border-color:#e2e8f0}.qr-btn.danger{background:#fff0f1;color:#b42336!important;border-color:#ffe0e4}.qr-btn.sm{padding:7px 10px;font-size:12px}.qr-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:20px}.qr-stat,.qr-card{background:white;border:1px solid #e5eaf2;border-radius:16px;box-shadow:0 5px 18px rgba(31,52,79,.045)}.qr-stat{padding:20px;position:relative;overflow:hidden}.qr-stat:before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:#2563eb}.qr-stat.green:before{background:#059669}.qr-stat.amber:before{background:#d97706}.qr-stat.purple:before{background:#7c3aed}.qr-stat-label{font-size:12px;color:#718096;font-weight:650}.qr-stat-value{font-size:25px;font-weight:800;margin:9px 0 3px;color:#1c3048}.qr-stat-note{font-size:11px;color:#9aa6b5}.qr-card{overflow:hidden}.qr-toolbar{padding:21px;border-bottom:1px solid #e9eef5}.qr-toolbar h3{font-size:16px;font-weight:800;margin:0}.qr-search{display:flex;gap:9px;flex-wrap:wrap}.qr-search input{min-width:240px;border:1px solid #dce4ed;border-radius:9px;padding:10px 12px;font-size:13px}.qr-table-wrap{overflow-x:auto}.qr-table{width:100%;border-collapse:collapse;min-width:920px}.qr-table th{background:#24364d;color:white;padding:14px 15px;font-size:11px;letter-spacing:.5px;text-transform:uppercase;text-align:left;white-space:nowrap}.qr-table td{padding:16px 15px;border-bottom:1px solid #eef1f6;font-size:13px;vertical-align:middle}.qr-table tr:hover td{background:#f8fbff}.qr-code{font-weight:800;color:#1e40af}.qr-sub{font-size:11px;color:#94a3b8;margin-top:3px}.qr-pill{display:inline-block;padding:5px 9px;border-radius:20px;background:#eff6ff;color:#1d4ed8;font-size:11px;font-weight:750}.qr-meter{height:7px;width:92px;border-radius:20px;background:#e7edf4;overflow:hidden;margin-top:6px}.qr-meter>span{display:block;height:100%;background:#16a34a;border-radius:20px}.qr-actions{display:flex;gap:6px;flex-wrap:wrap}.qr-actions form{margin:0}.qr-footer{padding:16px 20px}.qr-empty{text-align:center;padding:50px 15px!important;color:#94a3b8}.qr-empty strong{display:block;color:#334155;margin-bottom:6px}@media(max-width:950px){.qr-stats{grid-template-columns:repeat(2,1fr)}}@media(max-width:550px){.qr-stats{grid-template-columns:1fr}.qr-search,.qr-search input{width:100%}.qr-header h2{font-size:23px}}
</style>
@php
    $pageReports = $reports->getCollection();
    $pageInspected = $pageReports->sum(fn($r) => (float) ($r->total_inspected ?? 0));
    $pagePassed = $pageReports->sum(fn($r) => (float) ($r->total_passed ?? 0));
    $pageFailed = $pageReports->sum(fn($r) => (float) ($r->total_failed ?? 0));
    $pageRate = $pageInspected > 0 ? ($pagePassed / $pageInspected) * 100 : 0;
@endphp
<div class="qr-wrap">
  <div class="qr-header">
    <div><div class="qr-eyebrow">Production / Quality Control</div><h2>Quality Reports</h2><div class="qr-muted">Monitor inspection results, acceptance rates and quality performance.</div></div>
    <a class="qr-btn" href="{{ route('production.quality-reports.create') }}">＋ Create Quality Report</a>
  </div>
  <div class="qr-stats">
    <div class="qr-stat"><div class="qr-stat-label">Total Reports</div><div class="qr-stat-value">{{ number_format($reports->total()) }}</div><div class="qr-stat-note">All matching records</div></div>
    <div class="qr-stat purple"><div class="qr-stat-label">Inspected Quantity</div><div class="qr-stat-value">{{ number_format($pageInspected, 2) }}</div><div class="qr-stat-note">Current page total</div></div>
    <div class="qr-stat green"><div class="qr-stat-label">Passed Quantity</div><div class="qr-stat-value">{{ number_format($pagePassed, 2) }}</div><div class="qr-stat-note">Current page total</div></div>
    <div class="qr-stat amber"><div class="qr-stat-label">Weighted Pass Rate</div><div class="qr-stat-value">{{ number_format($pageRate, 1) }}%</div><div class="qr-stat-note">Current page · {{ number_format($pageFailed, 2) }} failed</div></div>
  </div>
  <div class="qr-card">
    <div class="qr-toolbar"><div><h3>Report Register</h3><div class="qr-muted">Inspection reports and their quality outcomes</div></div><form class="qr-search" method="GET"><input name="search" value="{{ request('search') }}" placeholder="Search report number..."><button class="qr-btn" type="submit">Search</button>@if(request('search'))<a class="qr-btn secondary" href="{{ route('production.quality-reports.index') }}">Reset</a>@endif</form></div>
    <div class="qr-table-wrap"><table class="qr-table"><thead><tr><th>#</th><th>Report Number</th><th>Inspection</th><th>Total Inspected</th><th>Total Passed</th><th>Total Failed</th><th>Pass Rate</th><th>Actions</th></tr></thead><tbody>
      @forelse($reports as $report)
      @php
        $inspected = (float) ($report->total_inspected ?? 0);
        $passed = (float) ($report->total_passed ?? 0);
        $rate = $inspected > 0 ? ($passed / $inspected) * 100 : (float) ($report->pass_rate ?? 0);
      @endphp
      <tr><td>{{ $report->id }}</td><td><div class="qr-code">{{ $report->report_number ?? '—' }}</div><div class="qr-sub">Quality control report</div></td><td><span class="qr-pill">#{{ $report->inspection_id ?? '—' }}</span></td><td>{{ number_format($inspected, 2) }}</td><td style="color:#15803d;font-weight:750">{{ number_format($passed, 2) }}</td><td style="color:#b42336;font-weight:750">{{ number_format((float) ($report->total_failed ?? 0), 2) }}</td><td><strong>{{ number_format($rate, 1) }}%</strong><div class="qr-meter"><span style="width:{{ max(0, min(100, $rate)) }}%"></span></div></td><td><div class="qr-actions"><a class="qr-btn sm secondary" href="{{ route('production.quality-reports.show', $report->id) }}">View</a><a class="qr-btn sm secondary" href="{{ route('production.quality-reports.edit', $report->id) }}">Edit</a><form method="POST" action="{{ route('production.quality-reports.destroy', $report->id) }}" onsubmit="return confirm('Delete this quality report?')">@csrf @method('DELETE')<button class="qr-btn sm danger" type="submit">Delete</button></form></div></td></tr>
      @empty<tr><td colspan="8" class="qr-empty"><strong>No quality reports found</strong>Create a report to start tracking quality results.</td></tr>@endforelse
    </tbody></table></div><div class="qr-footer">{{ $reports->withQueryString()->links() }}</div>
  </div>
</div>
@endsection
