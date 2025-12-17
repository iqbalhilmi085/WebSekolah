@extends('layouts.app')

@section('title', 'Kwitansi Pembayaran')
@section('page-title', 'Kwitansi Pembayaran')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12 text-right">
            <a href="{{ route('pembayaran.print', $pembayaran->id) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="{{ route('pembayaran.download', $pembayaran->id) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm" id="kwitansi">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="mb-1"><strong>TKIT JAMILUL MU'MININ</strong></h2>
                <p class="mb-0">Jl. Pendidikan No. 123, Makassar</p>
                <p class="mb-0">Telp: 0411-000-000</p>
                <hr class="my-3">
                <h4 class="mb-0"><strong>KWITANSI PEMBAYARAN SPP</strong></h4>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="150"><strong>No. Kwitansi</strong></td>
                            <td>: KW/{{ $pembayaran->id }}/{{ $pembayaran->tanggal_bayar ? date('Y', strtotime($pembayaran->tanggal_bayar)) : date('Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Siswa</strong></td>
                            <td>: {{ $pembayaran->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>: {{ $pembayaran->siswa->nis }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: {{ $pembayaran->siswa->kelas }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="150"><strong>Jenis Pembayaran</strong></td>
                            <td>: {{ $pembayaran->jenis_pembayaran }}</td>
                        </tr>
                        @if($pembayaran->bulan)
                        <tr>
                            <td><strong>Bulan</strong></td>
                            <td>: {{ $pembayaran->bulan }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Tahun</strong></td>
                            <td>: {{ $pembayaran->tahun }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah</strong></td>
                            <td>: <strong class="text-primary">{{ $pembayaran->formatted_jumlah }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                @if ($pembayaran->status == 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($pembayaran->status == 'cicilan')
                                    <span class="badge badge-warning">Cicilan</span>
                                @else
                                    <span class="badge badge-danger">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($pembayaran->keterangan)
            <div class="mb-3">
                <strong>Keterangan:</strong> {{ $pembayaran->keterangan }}
            </div>
            @endif

            <div class="row mt-5">
                <div class="col-md-6 text-center">
                    <p>Yang Menerima,</p>
                    <br><br><br>
                    <p><strong>___________________</strong></p>
                    <p>Tata Usaha</p>
                </div>
                <div class="col-md-6 text-center">
                    <p>Yang Membayar,</p>
                    <br><br><br>
                    <p><strong>___________________</strong></p>
                    <p>{{ $pembayaran->siswa->nama_orangtua }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .navbar, .sidebar { display: none !important; }
        #kwitansi { border: none; box-shadow: none; }
    }
</style>
@endsection

