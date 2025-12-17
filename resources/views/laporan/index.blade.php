@extends('layouts.app')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-chart-bar"></i> Laporan Pembayaran</h1>
            <p class="text-muted">Laporan pembayaran SPP per bulan, kelas, atau siswa</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('laporan.export-pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('laporan.export-excel', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" min="2020" max="2100">
                </div>
                <div class="col-md-3">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Siswa</label>
                    <select name="siswa_id" class="form-control">
                        <option value="">Semua Siswa</option>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Transaksi</h5>
                    <h2>{{ $statistik['total_transaksi'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Jumlah</h5>
                    <h2>Rp {{ number_format($statistik['total_jumlah'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Lunas</h5>
                    <h2>{{ $statistik['lunas'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Belum Lunas</h5>
                    <h2>{{ $statistik['belum_lunas'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->siswa->nama }}</td>
                                <td>{{ $item->siswa->kelas }}</td>
                                <td>{{ $item->jenis_pembayaran }}</td>
                                <td>{{ $item->bulan ?? '-' }}</td>
                                <td>{{ $item->formatted_jumlah }}</td>
                                <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if ($item->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($item->status == 'cicilan')
                                        <span class="badge badge-warning">Cicilan</span>
                                    @else
                                        <span class="badge badge-danger">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

