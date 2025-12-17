@extends('layouts.parent')

@section('title', 'Notifikasi - Portal Orang Tua')

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3" style="color:#064e3b;">
                    <i class="fas fa-bell mr-2"></i>Notifikasi Pembayaran
                </h4>

                @if($notifikasi->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada notifikasi.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($notifikasi as $notif)
                            <div class="list-group-item {{ !$notif->is_read ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            @if($notif->type == 'pembayaran_diterima')
                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                            @elseif($notif->type == 'pembayaran_ditolak')
                                                <i class="fas fa-times-circle text-danger mr-2"></i>
                                            @else
                                                <i class="fas fa-clock text-warning mr-2"></i>
                                            @endif
                                            {{ $notif->title }}
                                        </h6>
                                        <p class="mb-1">{{ $notif->message }}</p>
                                        <small class="text-muted">{{ $notif->created_at->format('d F Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $notifikasi->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

