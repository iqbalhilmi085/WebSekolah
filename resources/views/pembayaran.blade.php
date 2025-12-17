<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran SPP | TKIT Jamilul Mu'minin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* ====== STYLE DASAR ====== */
    body {
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f7fdf7;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }

    /* ====== NAVBAR ====== */
    header.navbar {
      background-color: #FFFFFF;
      padding: 10px 0;
      border-bottom: none;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* Menambah shadow agar konsisten */
    }

    .navbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img {
        height: 40px;
    }

    .logo h2 {
      font-size: 18px;
      color: #1c6b09;
      margin: 0;
    }

    nav.nav-menu {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    nav.nav-menu .dropdown {
        position: relative;
    }

    nav.nav-menu .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 6px;
        top: 100%;
        left: 0;
    }

    nav.nav-menu .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        transition: background-color 0.2s;
    }

    nav.nav-menu .dropdown-content a:hover {
        background-color: #e0e0e0;
    }

    nav.nav-menu .dropdown:hover .dropdown-content {
        display: block;
    }

    nav.nav-menu a {
      color: #1c6b09;
      font-weight: 500;
      transition: 0.2s;
      padding: 5px 0;
    }

    nav.nav-menu a:hover {
      color: #125804;
    }

    nav.nav-menu a.active {
        color: #125804;
        font-weight: 600;
    }

    .btn-daftar {
      background-color: #2b7a0b;
      color: white;
      padding: 8px 15px;
      border-radius: 6px;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-daftar:hover {
      background-color: #206406;
    }

    /* ====== SECTION HERO / SPP ====== */
    .hero {
      background: linear-gradient(to right, #ebfbee, #ffffff);
      padding: 80px 0;
    }

    .hero .container {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 40px;
    }

    .hero-text {
      flex: 1;
      min-width: 280px;
    }

    .hero-text h1 {
      color: #1c6b09;
      font-size: 32px;
      margin-bottom: 15px;
    }

    .hero-text p {
      color: #444;
      font-size: 15px;
      line-height: 1.6;
    }

    .hero-form {
      flex: 1;
      min-width: 320px;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    .hero-form h2 {
      color: #1c6b09;
      margin-bottom: 20px;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: 500;
      color: #333;
    }

    input, select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    input:focus, select:focus {
      border-color: #1c6b09;
      outline: none;
      box-shadow: 0 0 4px rgba(43, 122, 11, 0.3);
    }

    .btn-submit {
      background-color: #2b7a0b;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background-color: #206406;
    }

    /* ====== REKENING BANK SECTION (Di halaman SPP) ====== */
    .bank-info {
        background-color: #e6fae6;
        padding: 30px;
        text-align: center;
        margin-top: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .bank-info h2 {
        color: #1c6b09;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .bank-details p {
        margin: 8px 0;
        font-size: 15px;
        color: #444;
    }

    .bank-details p strong {
        color: #2b7a0b;
        font-weight: 600;
    }

    .bank-details .account-number {
        font-size: 18px;
        font-weight: 700;
        color: #0056b3;
    }

    /* ====== FOOTER ====== */
    footer.footer {
      background-color: #f3fff3;
      padding: 20px;
      text-align: center;
      color: #1c6b09;
      font-weight: 500;
      border-top: 1px solid #d7efd7;
      margin-top: 50px;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
      .navbar .container {
          flex-direction: column;
          gap: 15px;
      }
      .nav-menu {
          flex-wrap: wrap;
          justify-content: center;
      }
      .hero .container {
        flex-direction: column;
        text-align: center;
      }

      .hero-text, .hero-form {
        width: 100%;
      }
      .hero-form h2, .bank-info h2 {
        font-size: 22px;
      }
      .bank-details .account-number {
          font-size: 16px;
      }
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header class="navbar">
    <div class="container">
      <div class="logo">
        <img src="assets/1000202431.png" alt="Logo TKIT">
        <h2>TKIT JAMILUL MU'MININ</h2>
      </div>
      <nav class="nav-menu">
        <a href="index.html">Home</a>
      </nav>
    </div>
  </header>

  <!-- ======= Bagian Pembayaran SPP ======= -->
  <section class="hero">
    <div class="container">
      <div class="hero-text">
        <h1>Pembayaran SPP</h1>
        <p>
          Silakan lakukan pembayaran SPP dengan mengisi form di samping.  
          Mohon pastikan data yang Anda masukkan sudah benar, termasuk upload bukti transfer yang jelas.
        </p>
        <p>
          Kami berterima kasih atas kerja sama orang tua dalam mendukung kegiatan belajar anak-anak di TKIT Jamilul Mu’minin.
        </p>
      </div>

      <div> <!-- Ini akan membungkus form dan info bank -->
          <div class="hero-form">
            <h2>Form Pembayaran</h2>
            <form id="formSPP">
              <label for="nama">Nama Siswa:</label>
              <input type="text" id="nama" name="nama" required placeholder="Masukkan nama siswa">

              <label for="kelas">Kelas:</label>
              <select id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="Playgroup">Playgroup</option>
                <option value="TK A">TK A</option>
                <option value="TK B">TK B</option>
              </select>

              <label for="bulan">Bulan Pembayaran:</label>
              <input type="month" id="bulan" name="bulan" required>

              <label for="nominal">Nominal (Rp):</label>
              <input type="number" id="nominal" name="nominal" placeholder="Contoh: 200000" required>

              <label for="bukti">Upload Bukti Pembayaran:</label>
              <input type="file" id="bukti" name="bukti" accept="image/*" required>

              <button type="submit" class="btn-submit">Kirim Pembayaran</button>
            </form>
          </div>

          <!-- ======= REKENING BANK SECTION ======= -->
          <section class="bank-info">
            <h2>Informasi Rekening Bank Sekolah</h2>
            <div class="bank-details">
              <p>Untuk kemudahan transaksi pembayaran, silakan gunakan rekening berikut:</p>
              <p>Bank: <strong>Bank Syariah Indonesia (BSI)</strong></p>
              <p>Nomor Rekening: <span class="account-number">777 888 999 000</span></p>
              <p>Atas Nama: <strong>TKIT Jamilul Mu'minin</strong></p>
              <p style="font-size: 14px; color: #666; margin-top: 20px;">
                Mohon pastikan nama penerima dan nomor rekening sudah benar sebelum melakukan transfer.
              </p>
            </div>
          </section>
      </div> <!-- Penutup div pembungkus form dan info bank -->

    </div>
  </section>

  <!-- ======= Footer ======= -->
  <footer class="footer">
    <p>&copy; 2025 TKIT Jamilul Mu’minin - Makassar. All rights reserved.</p>
  </footer>

  <script>
    const form = document.getElementById('formSPP');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const nama = form.nama.value;
      const kelas = form.kelas.value;
      const bulan = form.bulan.value;
      const nominal = form.nominal.value;
      alert(`Pembayaran SPP berhasil dikirim!\n\nNama: ${nama}\nKelas: ${kelas}\nBulan: ${bulan}\nNominal: Rp ${parseInt(nominal).toLocaleString('id-ID')}`);
      form.reset();
    });
  </script>

</body>
</html>