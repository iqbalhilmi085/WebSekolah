@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><i class="fas fa-cog"></i> Pengaturan Sistem</h1>
            <p class="text-muted">Kelola pengaturan sistem pembayaran SPP</p>
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

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Pengaturan Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="nominal_spp">Nominal SPP per Bulan (Rp)</label>
                    <input type="number" class="form-control" id="nominal_spp" name="nominal_spp" 
                           value="{{ $settings['nominal_spp']->value ?? '200000' }}" required>
                    <small class="form-text text-muted">Nominal SPP yang harus dibayar setiap bulan</small>
                </div>

                <div class="form-group">
                    <label for="tahun_ajaran">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" 
                           value="{{ $settings['tahun_ajaran']->value ?? date('Y') . '/' . (date('Y') + 1) }}" required>
                    <small class="form-text text-muted">Format: 2025/2026</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-school"></i> Data Sekolah</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_sekolah">Nama Sekolah</label>
                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" 
                           value="{{ $settings['nama_sekolah']->value ?? 'TKIT Jamilul Mu\'minin' }}" required>
                </div>

                <div class="form-group">
                    <label for="alamat_sekolah">Alamat Sekolah</label>
                    <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah" rows="2" required>{{ $settings['alamat_sekolah']->value ?? 'Jl. Pendidikan No. 123, Makassar' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="telepon_sekolah">Telepon Sekolah</label>
                    <input type="text" class="form-control" id="telepon_sekolah" name="telepon_sekolah" 
                           value="{{ $settings['telepon_sekolah']->value ?? '0411-000-000' }}" required>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-university"></i> Informasi Rekening</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_bank">Nama Bank</label>
                    <input type="text" class="form-control" id="nama_bank" name="nama_bank" 
                           value="{{ $settings['nama_bank']->value ?? 'Bank Syariah' }}" required>
                </div>

                <div class="form-group">
                    <label for="no_rekening">Nomor Rekening</label>
                    <input type="text" class="form-control" id="no_rekening" name="no_rekening" 
                           value="{{ $settings['no_rekening']->value ?? '123456789' }}" required>
                </div>

                <div class="form-group">
                    <label for="cp_tu">Kontak Person TU (WhatsApp)</label>
                    <input type="text" class="form-control" id="cp_tu" name="cp_tu" 
                           value="{{ $settings['cp_tu']->value ?? '0812-0000-1234' }}" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
            <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection

