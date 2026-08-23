@extends('inc.master')

@section('head')
    <title>Stock Analytics</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="mb-4">
            <h4><b>Stock Analytics</b></h4>
            <small class="text-muted">Inventory insights, alerts and warehouse values.</small>
        </div>
        <div class="card border-danger mb-4">
            <div class="card-header bg-danger bg-opacity-10">
                <strong>
                    <i class="bx bx-error"></i> Low Stock Alerts
                </strong>
                <span class="badge bg-danger float-end">{{ count($lowStockItems) }} items</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Warehouse</th>
                            <th>Available</th>
                            <th>Reorder Point</th>
                            <th>Est. Stockout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $item)
                            <tr>
                                <td><strong>{{ $item['product_name'] }}</strong></td>
                                <td>{{ $item['sku'] }}</td>
                                <td>{{ $item['warehouse'] }}</td>
                                <td class="text-danger fw-bold">{{ $item['available_qty'] }}</td>
                                <td>{{ $item['reorder_point'] }}</td>
                                <td>
                                    @if($item['days_until_stockout'] == 0)
                                        <span class="text-danger fw-bold">Today!</span>
                                    @elseif($item['days_until_stockout'] < 3)
                                        <span class="text-danger">{{ $item['days_until_stockout'] }} days</span>
                                    @else
                                        <span class="text-warning">{{ $item['days_until_stockout'] }} days</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No low stock items.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-white">
                <strong>
                    <i class="bx bx-trending-up"></i> Top Moving Products
                </strong>
            </div>
            <div class="card-body">
                @forelse($topMovingProducts as $index => $item)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div>
                                <strong>{{ $index + 1 }}. {{ $item['product_name'] }}</strong>
                                <small class="text-muted ms-2">{{ $item['sku'] }}</small>
                            </div>
                            <div>
                                <strong>{{ $item['total_sold'] }}</strong> sold
                                <span class="badge bg-primary ms-2">{{ $item['turnover_rate'] }}x</span>
                            </div>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-warning" style="width: {{ min(($item['turnover_rate'] / 10) * 100, 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">No movement data available.</div>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white">
                <strong>
                    <i class="bx bx-bar-chart"></i> Stock Value by Warehouse
                </strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($stockValueByWarehouse as $warehouse)
                        <div class="col-md-4">
                            <div class="bg-light rounded p-4">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $warehouse['warehouse'] }}</strong>
                                    <span class="text-muted">{{ $warehouse['sku_count'] }} SKUs</span>
                                </div>
                                <h3 class="fw-bold mt-3">{{ number_format($warehouse['value'], 2) }}</h3>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ min(($warehouse['value'] / 150000) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">No warehouse value data.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection