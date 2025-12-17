<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #064e3b;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #064e3b;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
        }
        .summary p {
            margin: 5px 0;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['nama_sekolah']->value ?? 'TKIT JAMILUL MU\'MININ' }}</h2>
        <p>{{ $settings['alamat_sekolah']->value ?? 'Jl. Pendidikan No. 123, Makassar' }}</p>
        <p>Telp: {{ $settings['telepon_sekolah']->value ?? '0411-000-000' }}</p>
    </div>

    <h3 style="text-align: center; margin-bottom: 15px;">LAPORAN PEMBAYARAN SPP</h3>

    <div class="info">
        <p><strong>Tahun:</strong> {{ $tahun }}</p>
        @if(request('bulan'))
        <p><strong>Bulan:</strong> {{ request('bulan') }}</p>
        @endif
        @if(request('kelas'))
        <p><strong>Kelas:</strong> {{ request('kelas') }}</p>
        @endif
        <p><strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Bulan</th>
                <th class="text-right">Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayaran as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->siswa->nis }}</td>
                <td>{{ $item->siswa->nama }}</td>
                <td>{{ $item->siswa->kelas }}</td>
                <td>{{ $item->jenis_pembayaran }}</td>
                <td>{{ $item->bulan ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ strtoupper($item->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data pembayaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>RINGKASAN:</strong></p>
        <p>Total Transaksi: {{ $statistik['total_transaksi'] }}</p>
        <p>Total Jumlah: Rp {{ number_format($statistik['total_jumlah'], 0, ',', '.') }}</p>
        <p>Lunas: {{ $statistik['lunas'] }}</p>
        <p>Belum Lunas: {{ $statistik['belum_lunas'] }}</p>
    </div>
</body>
</html>

