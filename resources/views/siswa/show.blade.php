@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detail Siswa</h1>
        </div>
        <div class="col-md-4">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if ($siswa->foto)
                        <img src="{{ url('/storage/'.$siswa->foto) }}" alt="Foto Siswa" class="img-fluid rounded-circle" style="max-width: 200px; margin-bottom: 20px;">
                    @else
                        <div class="bg-light rounded-circle d-inline-block" style="width: 200px; height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #ccc;"></i>
                        </div>
                    @endif
                    <h5>{{ $siswa->nama }}</h5>
                    <p class="text-muted">{{ $siswa->nis }}</p>
                    <div class="mt-3">
                        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>NIS:</strong> {{ $siswa->nis }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nama:</strong> {{ $siswa->nama }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Jenis Kelamin:</strong> 
                            @if ($siswa->jenis_kelamin == 'L')
                                Laki-laki
                            @else
                                Perempuan
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Lahir:</strong> {{ $siswa->tanggal_lahir->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Kelas:</strong> {{ $siswa->kelas }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            @if ($siswa->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Orang Tua</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Orang Tua:</strong> {{ $siswa->nama_orangtua }}
                        </div>
                        <div class="col-md-6">
                            <strong>No Telepon:</strong> {{ $siswa->no_telp }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Alamat:</strong> {{ $siswa->alamat }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Data Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if ($siswa->pembayaran->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa->pembayaran as $item)
                                        <tr>
                                            <td>{{ $item->jenis_pembayaran }}</td>
                                            <td>{{ $item->getFormattedJumlahAttribute() }}</td>
                                            <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if ($item->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif ($item->status == 'belum_lunas')
                                                    <span class="badge badge-danger">Belum Lunas</span>
                                                @else
                                                    <span class="badge badge-warning">Cicilan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada data pembayaran</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
