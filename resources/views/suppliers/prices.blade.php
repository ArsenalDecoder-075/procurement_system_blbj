@extends('layouts.admin')

@section('title', 'Harga Material - ' . $supplier->supplier_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-tags"></i> Harga Material - {{ $supplier->supplier_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Info Supplier -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <h5>Informasi Supplier</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="150">Kode Supplier</th>
                                        <td>{{ $supplier->supplier_code }}</td>
                                        <th width="150">Nama Supplier</th>
                                        <td>{{ $supplier->supplier_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $supplier->phone ?? '-' }}</td>
                                        <th>Email</th>
                                        <td>{{ $supplier->email ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> Terdapat kesalahan dalam pengisian form:
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Material dengan Harga -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Material dengan Harga</h3>
                                    <div class="card-tools">
                                        <span class="badge badge-primary">
                                            {{ $existingMaterials->count() }} material
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($existingMaterials->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Material</th>
                                                    <th>Unit</th>
                                                    <th>Harga (Rp)</th>
                                                    <th>Lead Time (hari)</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($existingMaterials as $index => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $item->material->material_code }}</strong><br>
                                                        {{ $item->material->material_name }}
                                                    </td>
                                                    <td>{{ $item->material->unit }}</td>
                                                    <td class="text-right">
                                                        <strong>Rp {{ number_format($item->default_price, 0, ',', '.') }}</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-{{ $item->lead_time_days <= 3 ? 'success' : ($item->lead_time_days <= 7 ? 'warning' : 'danger') }}">
                                                            {{ $item->lead_time_days }} hari
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('suppliers.remove-material-price', $supplier->supplier_id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="material_id" value="{{ $item->material_id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle"></i> Belum ada material dengan harga untuk supplier ini.
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Tambah Harga Baru -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Tambah Harga Baru</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('suppliers.add-material-price', $supplier->supplier_id) }}" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label for="material_id">Pilih Material *</label>
                                            <select name="material_id" id="material_id"
                                                    class="form-control select2" required>
                                                <option value="">Pilih Material</option>
                                                @foreach($availableMaterials as $material)
                                                    <option value="{{ $material->material_id }}"
                                                            {{ old('material_id') == $material->material_id ? 'selected' : '' }}
                                                            data-unit="{{ $material->unit }}">
                                                        {{ $material->material_code }} - {{ $material->material_name }} ({{ $material->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('material_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Pilih material yang belum ada harganya</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Unit</label>
                                            <input type="text" class="form-control" id="unit_display" readonly value="{{ old('unit_display') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="default_price">Harga (Rp) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number"
                                                       name="default_price"
                                                       id="default_price"
                                                       class="form-control"
                                                       min="0"
                                                       step="100"
                                                       value="{{ old('default_price') }}"
                                                       required>
                                            </div>
                                            @error('default_price')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Harga per unit</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_time_days">Lead Time (hari) *</label>
                                            <input type="number"
                                                   name="lead_time_days"
                                                   id="lead_time_days"
                                                   class="form-control"
                                                   min="1"
                                                   max="90"
                                                   value="{{ old('lead_time_days') }}"
                                                   required>
                                            @error('lead_time_days')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Waktu pengiriman dalam hari (1-90)</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="bi bi-save"></i> Simpan Harga
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .callout {
        border-left: 5px solid #17a2b8;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .badge-success { background-color: #28a745; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-danger { background-color: #dc3545; }
    .badge-primary { background-color: #007bff; }
    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        height: 38px;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Pilih Material",
            width: '100%'
        });

        // Tampilkan unit ketika material dipilih
        $('#material_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            $('#unit_display').val(unit);
        });

        // Set unit jika ada nilai old
        @if(old('material_id'))
            const selectedMaterialId = '{{ old('material_id') }}';
            const selectedOption = $('#material_id option[value="' + selectedMaterialId + '"]');
            if (selectedOption.length > 0) {
                $('#unit_display').val(selectedOption.data('unit'));
            }
        @endif

        // Format input harga
        $('#default_price').on('input', function() {
            let value = $(this).val();
            if (value) {
                value = value.replace(/\D/g, '');
                $(this).val(value);
            }
        });
    });

    // Konfirmasi penghapusan
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus harga material ini?');
    }
</script>
@endpush
