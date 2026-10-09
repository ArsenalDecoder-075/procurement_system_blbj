@extends('layouts.admin')

@section('title', 'Data Penerimaan Barang')

@section('content')
<div class="container-fluid">
    {{-- Menampilkan Pesan Sukses atau Error dari Controller --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Penerimaan Barang</h3>
            <div class="card-tools">
                <a href="{{ route('receivings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Penerimaan
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Penerimaan</th>
                        <th>Nomor PO</th>
                        <th>Tanggal Terima</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th style="width: 200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivings as $receiving)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $receiving->receiving_number }}</td>
                        <td>{{ $receiving->purchaseOrder->po_number ?? '-' }}</td>
                        <td>{{ date('d/m/Y', strtotime($receiving->receiving_date)) }}</td>
                        <td>{{ $receiving->warehouse->warehouse_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $receiving->status == 'COMPLETE' ? 'badge-success' : 'badge-warning' }}">
                                {{ $receiving->status }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                {{-- TOMBOL DETAIL (BARU) --}}
                                <a href="{{ route('receivings.show', $receiving->receiving_id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>

                                {{-- TOMBOL EDIT --}}
                                <a href="{{ route('receivings.edit', $receiving->receiving_id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                {{-- TOMBOL HAPUS --}}
                                <form action="{{ route('receivings.destroy', $receiving->receiving_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data penerimaan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection