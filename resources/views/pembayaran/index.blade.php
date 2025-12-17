@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Data Pembayaran</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pembayaran
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('pembayaran.index') }}" method="GET" class="row">
                <div class="col-md-2">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun ?? date('Y') }}" min="2020" max="2100">
                </div>
                <div class="col-md-2">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Siswa</label>
                    <select name="siswa_id" class="form-control">
                        <option value="">Semua Siswa</option>
                        @foreach($siswaList ?? [] as $s)
                            <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="cicilan" {{ request('status') == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
            <div class="mt-2">
                <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Jenis Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Tanggal Bayar</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran as $item)
                            <tr>
                                <td>{{ $item->siswa->nama }}</td>
                                <td>{{ $item->jenis_pembayaran }}</td>
                                <td>{{ $item->formatted_jumlah }}</td>
                                <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>
                                    @if ($item->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif ($item->status == 'belum_lunas')
                                        <span class="badge badge-danger">Belum Lunas</span>
                                    @else
                                        <span class="badge badge-warning">Cicilan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pembayaran.show', $item->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pembayaran.kwitansi', $item->id) }}" class="btn btn-sm btn-success" title="Kwitansi">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="{{ route('pembayaran.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $pembayaran->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
