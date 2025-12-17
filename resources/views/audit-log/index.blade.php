@extends('layouts.app')

@section('title', 'Audit Log Pembayaran')
@section('page-title', 'Audit Log Pembayaran')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><i class="fas fa-history"></i> Audit Log Pembayaran</h1>
            <p class="text-muted">Riwayat aktivitas pembayaran dan perubahan data</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('audit-log.index') }}" method="GET" class="row">
                <div class="col-md-3">
                    <label>Aksi</label>
                    <select name="action" class="form-control">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Model</label>
                    <select name="model_type" class="form-control">
                        <option value="">Semua Model</option>
                        @foreach($models as $model)
                            <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>{{ $model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
            <div class="mt-2">
                <a href="{{ route('audit-log.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Model</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($auditLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>
                                    @if($log->action == 'create')
                                        <span class="badge badge-success">Create</span>
                                    @elseif($log->action == 'update')
                                        <span class="badge badge-warning">Update</span>
                                    @elseif($log->action == 'delete')
                                        <span class="badge badge-danger">Delete</span>
                                    @elseif($log->action == 'approve')
                                        <span class="badge badge-success">Approve</span>
                                    @elseif($log->action == 'reject')
                                        <span class="badge badge-danger">Reject</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->model_type }}</code></td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data audit log</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

