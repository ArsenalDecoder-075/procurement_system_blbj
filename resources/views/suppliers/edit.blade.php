@extends('layouts.admin')

@section('title', 'Edit Supplier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Supplier: {{ $supplier->supplier_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('suppliers.show', $supplier->supplier_id) }}"
                           class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('suppliers.update', $supplier->supplier_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- SUPPLIER CODE FIELD - INI YANG HILANG -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_code">Kode Supplier *</label>
                                    <input type="text"
                                           name="supplier_code"
                                           id="supplier_code"
                                           class="form-control @error('supplier_code') is-invalid @enderror"
                                           value="{{ old('supplier_code', $supplier->supplier_code) }}"
                                           required
                                           maxlength="20">
                                    @error('supplier_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 20 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_name">Nama Supplier *</label>
                                    <input type="text"
                                           name="supplier_name"
                                           id="supplier_name"
                                           class="form-control @error('supplier_name') is-invalid @enderror"
                                           value="{{ old('supplier_name', $supplier->supplier_name) }}"
                                           required
                                           maxlength="100">
                                    @error('supplier_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 100 karakter</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text"
                                           name="phone"
                                           id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $supplier->phone) }}"
                                           maxlength="20">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 20 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $supplier->email) }}"
                                           maxlength="100">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 100 karakter</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea name="address"
                                      id="address"
                                      rows="4"
                                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Supplier
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <small class="text-muted">* Wajib diisi</small>
                    <small class="text-muted float-right">
                        Dibuat: {{ $supplier->created_at->format('d/m/Y H:i') }}
                        @if($supplier->created_at != $supplier->updated_at)
                            <br>Diupdate: {{ $supplier->updated_at->format('d/m/Y H:i') }}
                        @endif
                    </small>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto focus
        $('#supplier_name').focus();

        // Phone number validation - allow + for international numbers
        $('#phone').on('input', function() {
            var value = $(this).val();
            // Allow numbers, plus, dash, space
            $(this).val(value.replace(/[^0-9+\-\s]/g, ''));
        });

        // Auto uppercase for supplier code
        $('#supplier_code').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>
@endsection
