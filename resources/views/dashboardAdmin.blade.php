@extends('layouts.app')

@section('title', 'Dashboard - TKIT Jamilul Mu\'minin')
@section('page-title', 'Dashboard')

@section('style')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-info h3 {
        color: #2d6a2e;
        margin: 0;
        font-size: 1rem;
    }

    .number {
        font-size: 1.8rem;
        font-weight: bold;
        color: #2d6a2e;
        margin: 0.5rem 0;
    }

    .stat-icon i {
        font-size: 2rem;
        color: #2d6a2e;
    }

    .section {
        margin-bottom: 2rem;
    }

    .section h2 {
        color: #2d6a2e;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .activities,
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .activity,
    .action-link {
        background-color: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        transition: transform 0.2s;
        text-decoration: none;
        color: #2d6a2e;
        font-weight: 600;
    }

    .activity:hover,
    .action-link:hover {
        transform: translateY(-4px);
        text-decoration: none;
        color: #2d6a2e;
    }

    .activity i,
    .action-link i {
        font-size: 1.5rem;
        color: #2d6a2e;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
    <p>Selamat datang di sistem manajemen TKIT Jamilul Mu'minin.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Siswa Aktif</h3>
                <div class="number">{{ $totalSiswaAktif }}</div>
                <p class="mb-0 text-muted">Jumlah siswa yang masih aktif.</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-children"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Siswa Nonaktif</h3>
                <div class="number">{{ $totalSiswaNonaktif }}</div>
                <p class="mb-0 text-muted">Siswa yang sudah keluar/nonaktif.</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-user-slash"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Pembayaran Bulan Ini</h3>
                <div class="number">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</div>
                <p class="mb-0 text-muted">Total pemasukan bulan berjalan.</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Transaksi Tahun Ini</h3>
                <div class="number">{{ $pembayaranLunas + $pembayaranBelumLunas }}</div>
                <p class="mb-0 text-muted">
                    Lunas: {{ $pembayaranLunas }}, Belum lunas / cicilan: {{ $pembayaranBelumLunas }}
                </p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
        </div>
    </div>

    <!-- Aksi Cepat ke halaman input & data -->
    <section class="section">
        <h2>Aksi Cepat</h2>
        <div class="quick-actions">
            <a href="{{ route('siswa.create') }}" class="action-link">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Siswa Baru</span>
            </a>
            <a href="{{ route('siswa.index') }}" class="action-link">
                <i class="fa-solid fa-users"></i>
                <span>Lihat Data Siswa</span>
            </a>
            <a href="{{ route('pembayaran.create') }}" class="action-link">
                <i class="fa-solid fa-money-check-dollar"></i>
                <span>Input Pembayaran</span>
            </a>
            <a href="{{ route('pembayaran.index') }}" class="action-link">
                <i class="fa-solid fa-wallet"></i>
                <span>Data Pembayaran</span>
            </a>
            <a href="{{ route('dashboard.status.pembayaran') }}" class="action-link">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Status Pembayaran Siswa</span>
            </a>
            <a href="{{ route('pendaftaran') }}" class="action-link">
                <i class="fa-solid fa-file-pen"></i>
                <span>Form Pendaftaran Umum</span>
            </a>
        </div>
    </section>

    <!-- Siswa & pembayaran terbaru -->
    <section class="section">
        <h2>Data Terbaru</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-users"></i> Siswa Terbaru
                    </div>
                    <div class="card-body">
                        @if($siswaTerbaru->isEmpty())
                            <p class="mb-0 text-muted">Belum ada data siswa.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($siswaTerbaru as $s)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $s->nama }} ({{ $s->kelas }})</span>
                                        <a href="{{ route('siswa.show', $s->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-money-check-dollar"></i> Pembayaran Terbaru
                    </div>
                    <div class="card-body">
                        @if($pembayaranTerbaru->isEmpty())
                            <p class="mb-0 text-muted">Belum ada data pembayaran.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($pembayaranTerbaru as $p)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $p->siswa->nama ?? '-' }}</strong><br>
                                            <small>{{ $p->jenis_pembayaran }} - {{ 'Rp ' . number_format($p->jumlah, 0, ',', '.') }}</small>
                                        </div>
                                        <a href="{{ route('pembayaran.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection