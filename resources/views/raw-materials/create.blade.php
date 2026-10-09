@extends('layouts.admin')

@section('title', 'Tambah Bahan Baku')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Bahan Baku Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('raw-materials.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_code">Kode Material *</label>
                                    <input type="text"
                                           class="form-control @error('material_code') is-invalid @enderror"
                                           id="material_code"
                                           name="material_code"
                                           value="{{ old('material_code', $nextCode) }}"
                                           required
                                           maxlength="20">
                                    @error('material_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 20 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_name">Nama Material *</label>
                                    <input type="text"
                                           class="form-control @error('material_name') is-invalid @enderror"
                                           id="material_name"
                                           name="material_name"
                                           value="{{ old('material_name') }}"
                                           required
                                           maxlength="100">
                                    @error('material_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 100 karakter</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit">Unit *</label>
                                    <select name="unit" id="unit"
                                            class="form-control @error('unit') is-invalid @enderror" required>
                                        <option value="">Pilih Unit</option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Unit</option>
                                        <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                        <option value="zak" {{ old('unit') == 'zak' ? 'selected' : '' }}>Zak</option>
                                        <option value="drum" {{ old('unit') == 'drum' ? 'selected' : '' }}>Drum</option>
                                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                        <option value="set" {{ old('unit') == 'set' ? 'selected' : '' }}>Set</option>
                                    </select>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Satuan pengukuran material</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custom_unit">Atau Unit Kustom</label>
                                    <input type="text"
                                           class="form-control @error('custom_unit') is-invalid @enderror"
                                           id="custom_unit"
                                           name="custom_unit"
                                           value="{{ old('custom_unit') }}"
                                           maxlength="20"
                                           placeholder="Contoh: karung, roll, etc.">
                                    @error('custom_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Kosongkan jika menggunakan unit standar</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Bahan Baku
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <small class="text-muted">* Wajib diisi</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto focus
        $('#material_name').focus();

        // Auto uppercase for material code
        $('#material_code').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

        // Toggle custom unit field
        $('#unit').on('change', function() {
            if ($(this).val() === '') {
                $('#custom_unit').prop('required', true);
            } else {
                $('#custom_unit').prop('required', false);
                $('#custom_unit').val('');
            }
        });

        // Auto-select custom unit if other is typed
        $('#custom_unit').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#unit').val('');
            }
        });
    });
</script>
@endsection
