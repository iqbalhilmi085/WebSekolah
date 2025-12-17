<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - TKIT Jamilul Mu'minin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Poppins', 'Segoe UI', sans-serif;
      background: linear-gradient(180deg,#f3f6f9 0%, #ffffff 100%);
      color: #0f172a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      -webkit-font-smoothing:antialiased;
    }

    .reset-container {
      width: 100%;
      max-width: 450px;
      background: #ffffff;
      border-radius: 14px;
      padding: 2.5rem;
      box-shadow: 0 12px 30px rgba(2,6,23,0.06);
      border: 1px solid rgba(2,6,23,0.03);
    }

    .reset-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .reset-header img {
      width: 64px;
      height: auto;
      margin-bottom: 1rem;
      border-radius: 8px;
    }

    .reset-header h2 {
      color: #064e3b;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      font-weight: 800;
    }

    .reset-header p {
      color: #64748b;
      font-size: 0.9rem;
    }

    .alert {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      font-size: 0.9rem;
    }

    .alert-success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }

    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 0.5rem;
      color: #0f172a;
      font-size: 0.9rem;
    }

    input {
      width: 100%;
      padding: 0.85rem 1rem;
      border-radius: 10px;
      border: 1px solid rgba(2,6,23,0.08);
      outline: none;
      font-size: 0.98rem;
      background: #fbfdff;
      transition: all 0.3s;
    }

    input:focus {
      border-color: rgba(6,95,70,0.9);
      box-shadow: 0 6px 18px rgba(6,95,70,0.08);
      background: #ffffff;
    }

    .btn-reset {
      width: 100%;
      padding: 0.92rem;
      background: linear-gradient(90deg,#0ea5a1 0%, #087f5b 100%);
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform .18s ease, box-shadow .18s ease;
      box-shadow: 0 8px 24px rgba(8,127,91,0.12);
      margin-top: 0.5rem;
    }

    .btn-reset:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 44px rgba(8,127,91,0.16);
    }

    .back-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
    }

    .back-link a {
      color: #0b815a;
      font-weight: 600;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .info-box {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
      color: #1e40af;
    }

    .info-box i {
      margin-right: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <div class="reset-header">
      <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo TKIT">
      <h2>Reset Password</h2>
      <p>Hanya untuk akun Orang Tua</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    <div class="info-box">
      <i class="fas fa-info-circle"></i>
      <strong>Catatan:</strong> Fitur ini hanya tersedia untuk akun Orang Tua. Masukkan email atau nomor HP yang terdaftar.
    </div>

    <form action="{{ route('password.email') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="email">
          <i class="fas fa-envelope"></i> Email atau Nomor HP
        </label>
        <input 
          type="text" 
          id="email" 
          name="email" 
          placeholder="Masukkan email atau nomor HP" 
          value="{{ old('email') }}"
          required
          autofocus
        >
      </div>

      <button type="submit" class="btn-reset">
        <i class="fas fa-paper-plane"></i> Kirim Link Reset Password
      </button>
    </form>

    <div class="back-link">
      <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left"></i> Kembali ke Login
      </a>
    </div>
  </div>
</body>
</html>

