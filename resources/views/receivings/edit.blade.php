@extends('layouts.admin')

@section('title', 'Edit Penerimaan Barang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    {{-- PERBAIKAN: Ganti $supplier menjadi $receiving --}}
                    <h3 class="card-title">Edit Penerimaan: {{ $receiving->receiving_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('receivings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    {{-- PERBAIKAN: Route diarahkan ke receivings.update --}}
                    <form action="{{ route('receivings.update', $receiving->receiving_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receiving_number">Nomor Penerimaan *</label>
                                    <input type="text" name="receiving_number" id="receiving_number"
                                           class="form-control @error('receiving_number') is-invalid @enderror"
                                           value="{{ old('receiving_number', $receiving->receiving_number) }}" required>
                                    @error('receiving_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receiving_date">Tanggal Penerimaan *</label>
                                    <input type="date" name="receiving_date" id="receiving_date"
                                           class="form-control @error('receiving_date') is-invalid @enderror"
                                           value="{{ old('receiving_date', $receiving->receiving_date) }}" required>
                                    @error('receiving_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po_id">Purchase Order (PO) *</label>
                                    <select name="po_id" id="po_id" class="form-control @error('po_id') is-invalid @enderror" required>
                                        @foreach($purchaseOrders as $po)
                                            <option value="{{ $po->po_id }}" {{ $receiving->po_id == $po->po_id ? 'selected' : '' }}>
                                                {{ $po->po_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id">Gudang Tujuan *</label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->warehouse_id }}" {{ $receiving->warehouse_id == $wh->warehouse_id ? 'selected' : '' }}>
                                                {{ $wh->warehouse_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status Penerimaan</label>
                            <select name="status" id="status" class="form-control">
                                <option value="PARTIAL" {{ $receiving->status == 'PARTIAL' ? 'selected' : '' }}>PARTIAL</option>
                                <option value="COMPLETE" {{ $receiving->status == 'COMPLETE' ? 'selected' : '' }}>COMPLETE</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Penerimaan
                            </button>
                            <a href="{{ route('receivings.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer">
                    <small class="text-muted">ID Penerimaan: {{ $receiving->receiving_id }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection