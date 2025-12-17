@extends('layouts.parent')

@section('title', 'Pembayaran SPP - Portal Orang Tua')
@section('page-title', 'Pembayaran SPP Anak')

@section('content')
    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3" style="color:#047857;">
                        <i class="fas fa-wallet mr-1"></i> Pilih Anak & Tahun Ajaran
                    </h5>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($anakList->isEmpty())
                        <div class="alert alert-info mb-0">
                            Belum ada data anak yang terhubung dengan akun orang tua ini.
                            Silakan hubungi TU untuk memastikan nomor HP orang tua telah tercatat dengan benar.
                        </div>
                    @else
                        <form action="{{ route('orangtua.pembayaran') }}" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-7">
                                    <label for="siswa_id">Pilih Anak</label>
                                    <select name="siswa_id" id="siswa_id" class="form-control" required>
                                        @foreach($anakList as $anak)
                                            <option value="{{ $anak->id }}" {{ $selectedSiswaId == $anak->id ? 'selected' : '' }}>
                                                {{ $anak->nama }} ({{ $anak->nis }} - {{ $anak->kelas }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Setiap orang tua hanya dapat melihat data anak yang terdaftar dengan nomor HP yang sama.
                                    </small>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="tahun">Tahun Ajaran</label>
                                    <select name="tahun" id="tahun" class="form-control">
                                        @php
                                            $tahunSaatIni = $tahun ?? now()->year;
                                        @endphp
                                        @if (!empty($tahunTersedia))
                                            @foreach ($tahunTersedia as $t)
                                                <option value="{{ $t }}" {{ $tahunSaatIni == $t ? 'selected' : '' }}>
                                                    {{ $t }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ $tahunSaatIni }}">{{ $tahunSaatIni }}</option>
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">
                                        Status SPP ditampilkan untuk 12 bulan di tahun yang dipilih.
                                    </small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Tampilkan Status SPP
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if ($siswa)
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3" style="color:#064e3b;">Informasi Siswa</h5>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Nama</strong><br>{{ $siswa->nama }}</p>
                                <p class="mb-1"><strong>NIS</strong><br>{{ $siswa->nis }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Kelas</strong><br>{{ $siswa->kelas }}</p>
                                <p class="mb-1"><strong>No. Telepon Ortu</strong><br>{{ $siswa->no_telp }}</p>
                                @if(!empty($statusAkademik))
                                    <p class="mb-1">
                                        <strong>Status Akademik</strong><br>
                                        <span class="badge badge-info">{{ $statusAkademik }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif {{-- jika siswa tidak ditemukan, cukup tampilkan info di bagian atas --}}
        </div>

        <div class="col-lg-5 mb-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3" style="color:#047857;">
                        <i class="fas fa-credit-card mr-1"></i> Cara Pembayaran SPP
                    </h5>
                    <p style="font-size:0.9rem;">
                        Pembayaran SPP dapat dilakukan dengan dua cara:
                    </p>
                    <ol style="font-size:0.9rem; padding-left:1.1rem;">
                        <li class="mb-2">
                            <strong>Cashless (Transfer Bank)</strong><br>
                            Rekening: <strong>Bank Syariah 123456789 a.n. TKIT Jamilul Mu'minin</strong><br>
                            Setelah transfer, silakan upload <strong>bukti transfer</strong> melalui form di bawah.
                        </li>
                        <li>
                            <strong>Tunai ke Tata Usaha (TU)</strong><br>
                            Pembayaran dapat dilakukan langsung di kantor TU atau menghubungi:<br>
                            <strong>CP TU: 0812-0000-1234 (WhatsApp)</strong>.
                        </li>
                    </ol>
                </div>
            </div>

            @if ($siswa)
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3" style="color:#047857;">
                            <i class="fas fa-upload mr-1"></i> Upload Bukti Transfer
                        </h5>
                        <form action="{{ route('orangtua.pembayaran.upload-bukti') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">Pilih Bulan (opsional)</option>
                                    @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                        <option value="{{ $bulan }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control"
                                       value="{{ now()->year }}" required>
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Jumlah Pembayaran (Rp)</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="2" class="form-control"
                                          placeholder="Contoh: SPP bulan Januari"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="bukti_pembayaran">Upload Bukti Transfer</label>
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                       class="form-control-file" accept="image/*" required>
                                <small class="form-text text-muted">
                                    Format JPG/PNG, maksimal 2 MB.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($siswa && !empty($bulanStatus))
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <h5 class="mb-3" style="color:#064e3b;">Status SPP per Bulan - Tahun {{ $tahun }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="thead-light">
                        <tr>
                            @foreach($bulanStatus as $info)
                                <th>{{ $info['bulan'] }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @foreach($bulanStatus as $info)
                                <td>
                                    @php $st = $info['status']; @endphp
                                    @if ($st === 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($st === 'cicilan')
                                        <span class="badge badge-warning">Cicilan</span>
                                    @elseif($st === 'belum_lunas')
                                        <span class="badge badge-danger">Belum Lunas</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($siswa && $riwayatPembayaran->isNotEmpty())
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <h5 class="mb-3" style="color:#064e3b;">Riwayat Pembayaran SPP</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th>Tanggal Bayar</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Bukti</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($riwayatPembayaran as $p)
                            <tr>
                                <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d-m-Y') : '-' }}</td>
                                <td>{{ $p->bulan ?? '-' }}</td>
                                <td>{{ $p->tahun }}</td>
                                <td>{{ $p->formatted_jumlah }}</td>
                                <td>
                                    @if ($p->status === 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($p->status === 'cicilan')
                                        <span class="badge badge-warning">Cicilan</span>
                                    @elseif($p->status_verifikasi === 'pending')
                                        <span class="badge badge-info">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge badge-danger">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->bukti_pembayaran)
                                        <a href="{{ url('/storage/'.$p->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($siswa && $riwayatPembayaran->isEmpty())
        <div class="alert alert-info mt-3">
            Belum ada riwayat pembayaran SPP yang tercatat untuk NIS <strong>{{ $siswa->nis }}</strong> pada tahun <strong>{{ $tahun }}</strong>.
        </div>
    @endif
@endsection


