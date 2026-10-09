@extends('layouts.admin')

@section('title', 'Tambah Penerimaan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Input Penerimaan Barang Baru</h3>
        </div>
        <div class="card-body">
            {{-- Menampilkan pesan error umum jika ada --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('receivings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiving_number">Nomor Penerimaan</label>
                            <input type="text" name="receiving_number" 
                                   class="form-control @error('receiving_number') is-invalid @enderror" 
                                   value="{{ old('receiving_number', $nextNumber) }}" required>
                            @error('receiving_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="po_id">Pilih Purchase Order (PO)</label>
                            <select name="po_id" class="form-control @error('po_id') is-invalid @enderror" required>
                                <option value="">-- Pilih PO --</option>
                                @foreach($purchaseOrders as $po)
                                    <option value="{{ $po->po_id }}" {{ old('po_id') == $po->po_id ? 'selected' : '' }}>
                                        {{ $po->po_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('po_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiving_date">Tanggal Penerimaan</label>
                            <input type="date" name="receiving_date" 
                                   class="form-control @error('receiving_date') is-invalid @enderror" 
                                   value="{{ old('receiving_date', date('Y-m-d')) }}" required>
                            @error('receiving_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="warehouse_id">Gudang Tujuan</label>
                            <select name="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}" {{ old('warehouse_id') == $wh->warehouse_id ? 'selected' : '' }}>
                                        {{ $wh->warehouse_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status Penerimaan</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="PARTIAL" {{ old('status') == 'PARTIAL' ? 'selected' : '' }}>PARTIAL</option>
                        <option value="COMPLETE" {{ old('status') == 'COMPLETE' ? 'selected' : '' }}>COMPLETE</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">* Jika status COMPLETE, stok akan otomatis bertambah.</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Penerimaan
                    </button>
                    <a href="{{ route('receivings.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection