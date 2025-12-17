@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Tambah Data Pembayaran</h1>
        </div>
        <div class="col-md-4">
            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="siswa_id">Siswa *</label>
                    <select class="form-control @error('siswa_id') is-invalid @enderror" 
                            id="siswa_id" name="siswa_id" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $item)
                            <option value="{{ $item->id }}" {{ old('siswa_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nis }} - {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jenis_pembayaran">Jenis Pembayaran *</label>
                    <input type="text" class="form-control @error('jenis_pembayaran') is-invalid @enderror" 
                           id="jenis_pembayaran" name="jenis_pembayaran" placeholder="Contoh: SPP, Uang Pangkal" 
                           value="{{ old('jenis_pembayaran') }}" required>
                    @error('jenis_pembayaran')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="jumlah">Jumlah *</label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" name="jumlah" step="0.01" value="{{ old('jumlah') }}" required>
                        @error('jumlah')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tanggal_bayar">Tanggal Bayar *</label>
                        <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                               id="tanggal_bayar" name="tanggal_bayar" value="{{ old('tanggal_bayar') }}" required>
                        @error('tanggal_bayar')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="bulan">Bulan</label>
                        <select class="form-control @error('bulan') is-invalid @enderror" 
                                id="bulan" name="bulan">
                            <option value="">-- Pilih Bulan --</option>
                            <option value="Januari" {{ old('bulan') == 'Januari' ? 'selected' : '' }}>Januari</option>
                            <option value="Februari" {{ old('bulan') == 'Februari' ? 'selected' : '' }}>Februari</option>
                            <option value="Maret" {{ old('bulan') == 'Maret' ? 'selected' : '' }}>Maret</option>
                            <option value="April" {{ old('bulan') == 'April' ? 'selected' : '' }}>April</option>
                            <option value="Mei" {{ old('bulan') == 'Mei' ? 'selected' : '' }}>Mei</option>
                            <option value="Juni" {{ old('bulan') == 'Juni' ? 'selected' : '' }}>Juni</option>
                            <option value="Juli" {{ old('bulan') == 'Juli' ? 'selected' : '' }}>Juli</option>
                            <option value="Agustus" {{ old('bulan') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                            <option value="September" {{ old('bulan') == 'September' ? 'selected' : '' }}>September</option>
                            <option value="Oktober" {{ old('bulan') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                            <option value="November" {{ old('bulan') == 'November' ? 'selected' : '' }}>November</option>
                            <option value="Desember" {{ old('bulan') == 'Desember' ? 'selected' : '' }}>Desember</option>
                        </select>
                        @error('bulan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tahun">Tahun *</label>
                        <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                               id="tahun" name="tahun" value="{{ old('tahun', date('Y')) }}" required>
                        @error('tahun')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ old('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="cicilan" {{ old('status') == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bukti_pembayaran">Bukti Pembayaran</label>
                    <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                           id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                    @error('bukti_pembayaran')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
