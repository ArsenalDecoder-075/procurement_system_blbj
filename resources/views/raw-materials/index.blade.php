@extends('layouts.admin')

@section('title', 'Data Bahan Baku')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h3 class="card-title">Daftar Bahan Baku</h3>
                    <div class="card-tools">
                        <a href="{{ route('raw-materials.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Bahan Baku
                        </a>
                    </div>
                </div> --}}
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Kode Material</th>
                                    <th>Nama Material</th>
                                    <th>Unit</th>
                                    <th>Dibuat</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials as $material)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $material->material_code }}</strong>
                                    </td>
                                    <td>{{ $material->material_name }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $material->unit }}</span>
                                    </td>
                                    <td>{{ $material->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('raw-materials.edit', $material->material_id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pen"></i>
                                                Edit
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $material->material_id }}">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $material->material_id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus bahan baku <strong>{{ $material->material_name }}</strong>?</p>
                                                <p class="text-danger">Perhatian: Aksi ini tidak dapat dibatalkan!</p>

                                                @php
                                                    $usedIn = [];
                                                    if (DB::table('stocks')->where('material_id', $material->material_id)->exists()) {
                                                        $usedIn[] = 'stok';
                                                    }
                                                    if (DB::table('purchase_order_details')->where('material_id', $material->material_id)->exists()) {
                                                        $usedIn[] = 'purchase order';
                                                    }
                                                    if (DB::table('receiving_details')->where('material_id', $material->material_id)->exists()) {
                                                        $usedIn[] = 'penerimaan barang';
                                                    }
                                                    if (DB::table('supplier_materials')->where('material_id', $material->material_id)->exists()) {
                                                        $usedIn[] = 'supplier materials';
                                                    }
                                                @endphp

                                                @if(count($usedIn) > 0)
                                                    <div class="alert alert-warning mt-2">
                                                        <strong>Peringatan:</strong> Material ini digunakan dalam:
                                                        <ul>
                                                            @foreach($usedIn as $usage)
                                                                <li>{{ $usage }}</li>
                                                            @endforeach
                                                        </ul>
                                                        Hapus data terkait terlebih dahulu.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                @if(count($usedIn) == 0)
                                                    <form action="{{ route('raw-materials.destroy', $material->material_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-danger" disabled>Hapus</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data bahan baku</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <div class="float-right">
                        Total: {{ $materials->count() }} Bahan Baku
                    </div>
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
        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Konfirmasi sebelum hapus
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data bahan baku akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
