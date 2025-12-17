<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #064e3b;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table td {
            padding: 8px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .footer {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        .footer div {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>TKIT JAMILUL MU'MININ</h2>
        <p>Jl. Pendidikan No. 123, Makassar</p>
        <p>Telp: 0411-000-000</p>
    </div>

    <div class="title">KWITANSI PEMBAYARAN SPP</div>

    <table>
        <tr>
            <td class="label">No. Kwitansi</td>
            <td>: KW/{{ $pembayaran->id }}/{{ $pembayaran->tanggal_bayar ? date('Y', strtotime($pembayaran->tanggal_bayar)) : date('Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Siswa</td>
            <td>: {{ $pembayaran->siswa->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td>: {{ $pembayaran->siswa->nis }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $pembayaran->siswa->kelas }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Pembayaran</td>
            <td>: {{ $pembayaran->jenis_pembayaran }}</td>
        </tr>
        @if($pembayaran->bulan)
        <tr>
            <td class="label">Bulan</td>
            <td>: {{ $pembayaran->bulan }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Tahun</td>
            <td>: {{ $pembayaran->tahun }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td>: <strong>{{ $pembayaran->formatted_jumlah }}</strong></td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: {{ strtoupper($pembayaran->status) }}</td>
        </tr>
    </table>

    @if($pembayaran->keterangan)
    <p><strong>Keterangan:</strong> {{ $pembayaran->keterangan }}</p>
    @endif

    <div class="footer">
        <div>
            <p>Yang Menerima,</p>
            <div class="signature-line">
                <p>Tata Usaha</p>
            </div>
        </div>
        <div>
            <p>Yang Membayar,</p>
            <div class="signature-line">
                <p>{{ $pembayaran->siswa->nama_orangtua }}</p>
            </div>
        </div>
    </div>
</body>
</html>

