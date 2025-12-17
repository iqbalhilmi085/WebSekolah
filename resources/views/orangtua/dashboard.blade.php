@extends('layouts.parent')

@section('title', 'Dashboard Orang Tua - TKIT Jamilul Mu\'minin')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column flex-md-row">
                    <div class="mr-md-4 mb-3 mb-md-0" style="flex:0 0 220px;">
                        <img src="{{ asset('image/kantor kepala sekolah.jpg') }}" alt="Profil Sekolah"
                             class="img-fluid rounded" style="object-fit:cover; max-height:180px; width:100%;">
                    </div>
                    <div>
                        <h4 class="mb-2" style="color:#064e3b;">Selamat datang, {{ Auth::user()->name }} 👋</h4>
                        <p class="text-muted mb-3" style="font-size:0.9rem;">
                            Portal ini khusus untuk orang tua/wali murid TKIT Jamilul Mu'minin untuk melihat profil sekolah
                            dan memantau pembayaran SPP anak setiap bulan.
                        </p>

                        <h5 class="mt-2 mb-2" style="color:#047857;">Profil Singkat Sekolah</h5>
                        <p class="mb-1" style="font-size:0.9rem;">
                            TKIT Jamilul Mu'minin berkomitmen membentuk generasi Qur'ani yang berakhlak mulia,
                            mandiri, komunikatif, dan ceria melalui pembelajaran yang menyenangkan dan bernuansa Islami.
                        </p>
                        <ul class="mt-2 mb-0" style="font-size:0.88rem; color:#4b5563;">
                            <li>Alamat: Jl. Pendidikan No. 123, Makassar</li>
                            <li>Telepon Sekolah: 0411-000-000</li>
                            <li>Jam Operasional: Senin - Jumat, 07.00 - 12.00 WITA</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3" style="color:#047857;">
                        <i class="fas fa-wallet mr-1"></i> Informasi Pembayaran SPP
                    </h5>
                    <p class="text-muted" style="font-size:0.88rem;">
                        Menu <strong>Pembayaran SPP</strong> digunakan untuk:
                    </p>
                    <ul style="font-size:0.88rem; color:#4b5563;">
                        <li>Melihat status dan riwayat pembayaran SPP anak per bulan.</li>
                        <li>Mengirim <strong>bukti transfer</strong> jika bayar melalui rekening sekolah.</li>
                        <li>Melihat informasi nomor rekening dan kontak TU.</li>
                    </ul>

                    <div class="alert alert-success mt-3 mb-1" style="font-size:0.85rem;">
                        Untuk mulai, buka menu
                        <strong><a href="{{ route('orangtua.pembayaran') }}">Pembayaran SPP</a></strong>
                        di bagian atas, lalu masukkan NIS anak Bapak/Ibu.
                    </div>
                </div>
            </div>

            @if($siswaUtama)
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="mb-2" style="color:#047857;">
                            <i class="fas fa-child mr-1"></i> Data Anak
                        </h6>
                        <p class="mb-1" style="font-size:0.9rem;"><strong>Nama:</strong> {{ $siswaUtama->nama }}</p>
                        <p class="mb-1" style="font-size:0.9rem;"><strong>NIS:</strong> {{ $siswaUtama->nis }}</p>
                        <p class="mb-1" style="font-size:0.9rem;"><strong>Kelas:</strong> {{ $siswaUtama->kelas }}</p>
                        <p class="mb-0" style="font-size:0.85rem;">
                            Untuk detail pembayaran, silakan buka menu <strong>Pembayaran SPP</strong>.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($ringkasan && $siswaUtama)
        <div class="row mt-2">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1" style="color:#064e3b;">Ringkasan SPP Tahun {{ $ringkasan['tahun'] }}</h6>
                            <p class="mb-0 text-muted" style="font-size:0.86rem;">
                                Total transaksi: {{ $ringkasan['total_transaksi'] }} |
                                Lunas: {{ $ringkasan['total_lunas'] }} |
                                Belum / cicilan: {{ $ringkasan['total_belum'] }}
                            </p>
                        </div>
                        <a href="{{ route('orangtua.pembayaran', ['siswa_id' => $siswaUtama->id ?? null, 'tahun' => $ringkasan['tahun']]) }}"
                           class="btn btn-outline-success btn-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($anakList->count() > 1)
        <div class="row mt-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="mb-3" style="color:#047857;">
                            <i class="fas fa-children mr-1"></i> Daftar Anak yang Terdaftar
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($anakList as $anak)
                                    <tr>
                                        <td>{{ $anak->nama }}</td>
                                        <td>{{ $anak->nis }}</td>
                                        <td>{{ $anak->kelas }}</td>
                                        <td>
                                            <a href="{{ route('orangtua.pembayaran', ['siswa_id' => $anak->id]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Lihat SPP
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


