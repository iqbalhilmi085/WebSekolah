@extends('layouts.app')

@section('title', 'Backup Data Sistem')
@section('page-title', 'Backup Data Sistem')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-database"></i> Backup Data Sistem</h1>
            <p class="text-muted">Kelola backup database sistem</p>
        </div>
        <div class="col-md-4 text-right">
            <form action="{{ route('backup.create') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Buat Backup Baru
                </button>
            </form>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if(count($backupFiles) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backupFiles as $index => $file)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $file['name'] }}</code></td>
                                    <td>{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                    <td>{{ $file['date'] }}</td>
                                    <td>
                                        <a href="{{ route('backup.download', $file['name']) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <form action="{{ route('backup.delete', $file['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus backup ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada backup yang tersedia. Klik tombol "Buat Backup Baru" untuk membuat backup pertama.
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h5><i class="fas fa-info-circle"></i> Informasi Backup</h5>
            <ul>
                <li>Backup mencakup semua data: Users, Siswa, Pembayaran, Settings, Notifications, dan Audit Logs</li>
                <li>File backup disimpan di: <code>storage/app/backups/</code></li>
                <li>Disarankan untuk membuat backup secara berkala (minimal 1x per bulan)</li>
                <li>Download backup dan simpan di tempat yang aman</li>
            </ul>
        </div>
    </div>
</div>
@endsection

