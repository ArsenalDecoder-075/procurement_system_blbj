@extends('layouts.admin')

@section('title', isset($supplierMaterial) ? 'Edit Harga' : 'Tambah Harga Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ isset($supplierMaterial) ? 'Edit Harga & Lead Time' : 'Tambah Harga & Lead Time Baru' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ isset($supplierMaterial) ? route('supplier-materials.update', $supplierMaterial->id) : route('supplier-materials.store') }}" method="POST">
                        @csrf
                        @if(isset($supplierMaterial))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier *</label>
                                    <select name="supplier_id" id="supplier_id"
                                            class="form-control select2 @error('supplier_id') is-invalid @enderror" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}"
                                                {{ old('supplier_id', isset($supplierMaterial) ? $supplierMaterial->supplier_id : '') == $supplier->supplier_id ? 'selected' : '' }}>
                                                {{ $supplier->supplier_code }} - {{ $supplier->supplier_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_id">Material *</label>
                                    <select name="material_id" id="material_id"
                                            class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                        <option value="">Pilih Material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->material_id }}"
                                                data-unit="{{ $material->unit }}"
                                                {{ old('material_id', isset($supplierMaterial) ? $supplierMaterial->material_id : '') == $material->material_id ? 'selected' : '' }}>
                                                {{ $material->material_code }} - {{ $material->material_name }} ({{ $material->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_price">Harga Default (Rp) *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="default_price"
                                               id="default_price"
                                               class="form-control @error('default_price') is-invalid @enderror"
                                               value="{{ old('default_price', isset($supplierMaterial) ? $supplierMaterial->default_price : '') }}"
                                               min="0"
                                               step="100"
                                               required>
                                    </div>
                                    @error('default_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Harga per unit material</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lead_time_days">Lead Time (hari) *</label>
                                    <input type="number"
                                           name="lead_time_days"
                                           id="lead_time_days"
                                           class="form-control @error('lead_time_days') is-invalid @enderror"
                                           value="{{ old('lead_time_days', isset($supplierMaterial) ? $supplierMaterial->lead_time_days : '') }}"
                                           min="1"
                                           max="90"
                                           required>
                                    @error('lead_time_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Waktu pengiriman dalam hari</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_order_quantity">Minimal Order</label>
                                    <input type="number"
                                           name="min_order_quantity"
                                           id="min_order_quantity"
                                           class="form-control @error('min_order_quantity') is-invalid @enderror"
                                           value="{{ old('min_order_quantity', isset($supplierMaterial) ? $supplierMaterial->min_order_quantity : '') }}"
                                           min="0"
                                           step="0.01">
                                    <small class="form-text text-muted">Kuantitas minimal pemesanan</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="is_active" id="status" class="form-control">
                                        <option value="1" {{ old('is_active', isset($supplierMaterial) ? $supplierMaterial->is_active : 1) ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="0" {{ old('is_active', isset($supplierMaterial) ? $supplierMaterial->is_active : 1) ? '' : 'selected' }}>
                                            Non-Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea name="notes"
                                      id="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3">{{ old('notes', isset($supplierMaterial) ? $supplierMaterial->notes : '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Catatan khusus tentang harga atau lead time</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                            <a href="{{ route('supplier-materials.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Pilih...",
            allowClear: true
        });

        // Auto-format price input
        $('#default_price').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });

        // Show unit when material selected
        $('#material_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            $('#unit-display').text(unit);
        });
    });
</script>
@endpush
