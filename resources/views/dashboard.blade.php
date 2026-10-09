@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalStock, 2, ',', '.') }}</h3>
                    <p>Total Stok Barang</p>
                </div>
                <div class="icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <a href="{{ route('stocks.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalMaterials }}</h3>
                    <p>Jenis Bahan Baku</p>
                </div>
                <div class="icon">
                    <i class="bi bi-boxes"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalSuppliers }}</h3>
                    <p>Total Supplier</p>
                </div>
                <div class="icon">
                    <i class="bi bi-people"></i>
                </div>
                <a href="{{ route('suppliers.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalPurchaseOrders }}</h3>
                    <p>Total Purchase Order</p>
                </div>
                <div class="icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <a href="{{ route('purchase-orders.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <!-- Recent Purchase Orders -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase Order Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total Items</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchaseOrders as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->supplier->supplier_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{
                                            $po->status == 'APPROVED' ? 'success' :
                                            ($po->status == 'DRAFT' ? 'warning' :
                                            ($po->status == 'CLOSED' ? 'info' : 'secondary'))
                                        }}">
                                            {{ $po->status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $po->details_count ?? 0 }}
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase-orders.show', $po->po_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data Purchase Order</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Receivings -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Penerimaan Barang Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Receiving</th>
                                    <th>PO Number</th>
                                    <th>Tanggal</th>
                                    <th>Gudang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReceivings as $receiving)
                                <tr>
                                    <td>{{ $receiving->receiving_number }}</td>
                                    <td>{{ $receiving->purchaseOrder->po_number ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($receiving->receiving_date)->format('d/m/Y') }}</td>
                                    <td>{{ $receiving->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{
                                            $receiving->status == 'COMPLETE' ? 'success' : 'warning'
                                        }}">
                                            {{ $receiving->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('receivings.show', $receiving->receiving_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data Penerimaan Barang</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <!-- Low Stock Materials -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bahan Baku Stok Rendah</h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">Threshold: < 10</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Stok</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockMaterials as $stock)
                                <tr>
                                    <td>{{ $stock->material->material_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ number_format($stock->quantity, 2) }}</span>
                                    </td>
                                    <td>{{ $stock->material->unit ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        Semua stok bahan baku aman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Supplier Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Statistik Supplier</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <h4>{{ $totalSuppliers }}</h4>
                            <p class="text-muted">Total Supplier</p>
                        </div>
                        <div class="col-6 text-center">
                            <h4>{{ $totalWarehouses }}</h4>
                            <p class="text-muted">Total Gudang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Buat Purchase Order Baru
                        </a>
                        <a href="{{ route('receivings.create') }}" class="btn btn-success">
                            <i class="bi bi-truck"></i> Buat Penerimaan Barang
                        </a>
                        <a href="{{ route('raw-materials.create') }}" class="btn btn-info">
                            <i class="bi bi-box"></i> Tambah Bahan Baku
                        </a>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-warning">
                            <i class="bi bi-person-plus"></i> Tambah Supplier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto refresh dashboard every 60 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.location.reload();
        }, 60000); // 60 seconds
    });
</script>
@endsection
