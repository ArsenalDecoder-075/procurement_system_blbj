@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Supplier</h3>
                    <div class="card-tools">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Supplier
                        </a>
                    </div>
                </div>
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
                                    <th>Kode Supplier</th>
                                    <th>Nama Supplier</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Jumlah Material</th>
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->supplier_code }}</td>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>{{ $supplier->phone ?? '-' }}</td>
                                    <td>{{ $supplier->email ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">
                                            {{ $supplier->supplierMaterials->count() ?? 0 }} material
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <!-- Tombol Detail/Harga -->
                                            <a href="{{ route('suppliers.prices', $supplier->supplier_id) }}"
                                               class="btn btn-info btn-sm"
                                               title="Kelola Harga Material">
                                                <i class="bi bi-tags"></i> Harga
                                            </a>

                                            <!-- Tombol Edit -->
                                            <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pen"></i> Edit
                                            </a>

                                            <!-- Tombol Delete -->
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $supplier->supplier_id }}">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $supplier->supplier_id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus supplier <strong>{{ $supplier->supplier_name }}</strong>?</p>
                                                <p class="text-danger">Perhatian: Aksi ini tidak dapat dibatalkan!</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data supplier</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <div class="float-right">
                        Total: {{ $suppliers->count() }} Supplier
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

        // Konfirmasi sebelum hapus supplier
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data supplier akan dihapus permanen!",
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
