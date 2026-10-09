@extends('layouts.admin')

@section('title', 'Edit Bahan Baku')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Bahan Baku: {{ $rawMaterial->material_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('raw-materials.update', $rawMaterial->material_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_code">Kode Material *</label>
                                    <input type="text"
                                           name="material_code"
                                           id="material_code"
                                           class="form-control @error('material_code') is-invalid @enderror"
                                           value="{{ old('material_code', $rawMaterial->material_code) }}"
                                           required
                                           maxlength="20">
                                    @error('material_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Maksimal 20 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="material_name">Nama Material *</label>
                                    <input type="text"
                                           name="material_name"
                                           id="material_name"
                                           class="form-control @error('material_name') is-invalid @enderror"
                                           value="{{ old('material_name', $rawMaterial->material_name) }}"
                                           required
                                           maxlength="100">
                                    @error('material_name')
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
                                    <label for="unit">Unit *</label>
                                    <select name="unit" id="unit"
                                            class="form-control @error('unit') is-invalid @enderror" required>
                                        <option value="">Pilih Unit</option>
                                        <option value="kg" {{ old('unit', $rawMaterial->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="g" {{ old('unit', $rawMaterial->unit) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                        <option value="pcs" {{ old('unit', $rawMaterial->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="unit" {{ old('unit', $rawMaterial->unit) == 'unit' ? 'selected' : '' }}>Unit</option>
                                        <option value="meter" {{ old('unit', $rawMaterial->unit) == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                                        <option value="liter" {{ old('unit', $rawMaterial->unit) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                        <option value="zak" {{ old('unit', $rawMaterial->unit) == 'zak' ? 'selected' : '' }}>Zak</option>
                                        <option value="drum" {{ old('unit', $rawMaterial->unit) == 'drum' ? 'selected' : '' }}>Drum</option>
                                        <option value="box" {{ old('unit', $rawMaterial->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                        <option value="set" {{ old('unit', $rawMaterial->unit) == 'set' ? 'selected' : '' }}>Set</option>
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
                                           placeholder="Contoh: karung, roll, etc."
                                           {{ !in_array($rawMaterial->unit, ['kg', 'g', 'pcs', 'unit', 'meter', 'liter', 'zak', 'drum', 'box', 'set']) ? 'required' : '' }}>
                                    @error('custom_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <small class="form-text text-muted">
                                        @if(!in_array($rawMaterial->unit, ['kg', 'g', 'pcs', 'unit', 'meter', 'liter', 'zak', 'drum', 'box', 'set']))
                                            Unit kustom saat ini: <strong>{{ $rawMaterial->unit }}</strong>
                                        @else
                                            Kosongkan jika menggunakan unit standar
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Bahan Baku
                            </button>
                            <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <small class="text-muted">* Wajib diisi</small>
                    <small class="text-muted float-right">
                        Dibuat: {{ $rawMaterial->created_at->format('d/m/Y H:i') }}
                        @if($rawMaterial->created_at != $rawMaterial->updated_at)
                            <br>Diupdate: {{ $rawMaterial->updated_at->format('d/m/Y H:i') }}
                        @endif
                    </small>
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

        // Pre-fill custom unit if it's a custom value
        const currentUnit = '{{ $rawMaterial->unit }}';
        const standardUnits = ['kg', 'g', 'pcs', 'unit', 'meter', 'liter', 'zak', 'drum', 'box', 'set'];

        if (!standardUnits.includes(currentUnit) && currentUnit !== '') {
            $('#unit').val('');
            $('#custom_unit').val(currentUnit);
        }
    });
</script>
@endsection
