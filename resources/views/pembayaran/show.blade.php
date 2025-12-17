@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detail Pembayaran</h1>
        </div>
        <div class="col-md-4">
            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Siswa:</strong> {{ $pembayaran->siswa->nama }}
                        </div>
                        <div class="col-md-6">
                            <strong>NIS:</strong> {{ $pembayaran->siswa->nis }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Jenis Pembayaran:</strong> {{ $pembayaran->jenis_pembayaran }}
                        </div>
                        <div class="col-md-6">
                            <strong>Jumlah:</strong> {{ $pembayaran->formatted_jumlah }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Bayar:</strong> {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Bulan:</strong> {{ $pembayaran->bulan ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tahun:</strong> {{ $pembayaran->tahun }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            @if ($pembayaran->status == 'lunas')
                                <span class="badge badge-success">Lunas</span>
                            @elseif ($pembayaran->status == 'belum_lunas')
                                <span class="badge badge-danger">Belum Lunas</span>
                            @else
                                <span class="badge badge-warning">Cicilan</span>
                            @endif
                        </div>
                    </div>
                    @if ($pembayaran->keterangan)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Keterangan:</strong><br>
                                {{ $pembayaran->keterangan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if ($pembayaran->bukti_pembayaran)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ url('/storage/'.$pembayaran->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded">
                        <div class="mt-3">
                            <a href="{{ url('/storage/'.$pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Siswa</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $pembayaran->siswa->nama }}</p>
                    <p><strong>Kelas:</strong> {{ $pembayaran->siswa->kelas }}</p>
                    <p><strong>No Telepon:</strong> {{ $pembayaran->siswa->no_telp }}</p>
                    <p><strong>Status:</strong> 
                        @if ($pembayaran->siswa->status == 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Nonaktif</span>
                        @endif
                    </p>
                    <a href="{{ route('siswa.show', $pembayaran->siswa->id) }}" class="btn btn-sm btn-primary btn-block mt-3">
                        <i class="fas fa-user"></i> Lihat Detail Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
