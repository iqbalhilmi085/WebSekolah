@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran Cashless')
@section('page-title', 'Verifikasi Pembayaran Cashless')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-check-circle"></i> Verifikasi Pembayaran Cashless</h1>
            <p class="text-muted">Verifikasi bukti transfer pembayaran dari orang tua</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group" role="group">
                <a href="{{ route('verifikasi.index', ['status' => 'pending']) }}" 
                   class="btn btn-{{ request('status') == 'pending' || !request('status') ? 'primary' : 'outline-primary' }}">
                    Pending
                </a>
                <a href="{{ route('verifikasi.index', ['status' => 'diterima']) }}" 
                   class="btn btn-{{ request('status') == 'diterima' ? 'success' : 'outline-success' }}">
                    Diterima
                </a>
                <a href="{{ route('verifikasi.index', ['status' => 'ditolak']) }}" 
                   class="btn btn-{{ request('status') == 'ditolak' ? 'danger' : 'outline-danger' }}">
                    Ditolak
                </a>
            </div>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal Upload</th>
                            <th>Nama Siswa</th>
                            <th>Bulan/Tahun</th>
                            <th>Jumlah</th>
                            <th>Bukti Transfer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $item->siswa->nama }}</strong><br><small>{{ $item->siswa->kelas }}</small></td>
                                <td>{{ $item->bulan ?? '-' }} / {{ $item->tahun }}</td>
                                <td>{{ $item->formatted_jumlah }}</td>
                                <td>
                                    @if ($item->bukti_pembayaran)
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#buktiModal{{ $item->id }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                        <a href="{{ url('/storage/'.$item->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-external-link-alt"></i> Buka
                                        </a>
                                        
                                        <!-- Modal Bukti Pembayaran -->
                                        <div class="modal fade" id="buktiModal{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bukti Pembayaran - {{ $item->siswa->nama }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ url('/storage/'.$item->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 70vh;" onerror="this.src='{{ asset('image/logo putih.jpg') }}'; this.onerror=null;">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ url('/storage/'.$item->bukti_pembayaran) }}" target="_blank" class="btn btn-primary" download>
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_verifikasi == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status_verifikasi == 'diterima')
                                        <span class="badge badge-success">Diterima</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_verifikasi == 'pending')
                                        <form action="{{ route('verifikasi.approve', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Terima pembayaran ini?')">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $item->id }}">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                        
                                        <!-- Modal Reject -->
                                        <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('verifikasi.reject', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Alasan Penolakan *</label>
                                                                <textarea name="alasan_penolakan" class="form-control" rows="3" required 
                                                                          placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted">
                                            Diverifikasi oleh: {{ $item->verifier->name ?? '-' }}<br>
                                            {{ $item->verified_at ? $item->verified_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pembayaran yang perlu diverifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $pembayaran->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

