<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran SPP</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 5px 0; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; }
        .footer { margin-top: 50px; }
        .footer div { width: 50%; float: left; text-align: center; }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><strong>TKIT JAMILUL MU'MININ</strong></h2>
        <p>Jl. Pendidikan No. 123, Makassar</p>
        <p>Telp: 0411-000-000</p>
        <hr>
        <h3><strong>KWITANSI PEMBAYARAN SPP</strong></h3>
    </div>

    <table>
        <tr>
            <td width="200"><strong>No. Kwitansi</strong></td>
            <td>: KW/{{ $pembayaran->id }}/{{ $pembayaran->tanggal_bayar ? date('Y', strtotime($pembayaran->tanggal_bayar)) : date('Y') }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>: {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Siswa</strong></td>
            <td>: {{ $pembayaran->siswa->nama }}</td>
        </tr>
        <tr>
            <td><strong>NIS</strong></td>
            <td>: {{ $pembayaran->siswa->nis }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>: {{ $pembayaran->siswa->kelas }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Pembayaran</strong></td>
            <td>: {{ $pembayaran->jenis_pembayaran }}</td>
        </tr>
        @if($pembayaran->bulan)
        <tr>
            <td><strong>Bulan</strong></td>
            <td>: {{ $pembayaran->bulan }}</td>
        </tr>
        @endif
        <tr>
            <td><strong>Tahun</strong></td>
            <td>: {{ $pembayaran->tahun }}</td>
        </tr>
        <tr>
            <td><strong>Jumlah</strong></td>
            <td>: <strong>{{ $pembayaran->formatted_jumlah }}</strong></td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>: {{ strtoupper($pembayaran->status) }}</td>
        </tr>
    </table>

    @if($pembayaran->keterangan)
    <p><strong>Keterangan:</strong> {{ $pembayaran->keterangan }}</p>
    @endif

    <div class="footer">
        <div>
            <p>Yang Menerima,</p>
            <br><br><br>
            <p>___________________</p>
            <p>Tata Usaha</p>
        </div>
        <div>
            <p>Yang Membayar,</p>
            <br><br><br>
            <p>___________________</p>
            <p>{{ $pembayaran->siswa->nama_orangtua }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

