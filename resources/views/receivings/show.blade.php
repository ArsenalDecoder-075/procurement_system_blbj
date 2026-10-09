@extends('layouts.admin')

@section('title', 'Detail Penerimaan Barang')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Penerimaan: <strong>{{ $receiving->receiving_number }}</strong></h3>
            <div class="card-tools">
                <a href="{{ route('receivings.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th width="150">Nomor PO</th><td>: {{ $receiving->purchaseOrder->po_number ?? '-' }}</td></tr>
                        <tr><th>Gudang Tujuan</th><td>: {{ $receiving->warehouse->warehouse_name ?? '-' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th width="150">Tanggal Terima</th><td>: {{ date('d/m/Y', strtotime($receiving->receiving_date)) }}</td></tr>
                        <tr><th>Status</th><td>: <span class="badge badge-success">{{ $receiving->status }}</span></td></tr>
                    </table>
                </div>
            </div>

            <h5>Rincian Item yang Diterima:</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-light">
                        <th>No</th>
                        <th>Nama Material</th>
                        <th class="text-right">Jumlah Diterima</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->material_name }}</td>
                        <td class="text-right">{{ number_format($detail->quantity_received, 2) }}</td>
                        <td>{{ $detail->unit }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Tidak ada rincian barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection