<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login TKIT Jamilul Mu'minin</title>
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
      -webkit-font-smoothing:antialiased;
    }

    .login-container {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      width: 100%;
      padding: 3rem 2rem;
    }

    .login-left {
      flex: 0 0 58%;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .photo-card {
      background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95));
      padding: 0.6rem;
      border-radius: 16px;
      max-width: 960px;
      width: 100%;
      box-shadow: 0 18px 40px rgba(2,6,23,0.08);
      border: 1px solid rgba(2,6,23,0.04);
      overflow: hidden;
      position: relative;
    }

    .login-image {
      width: 100%;
      height: auto;
      max-height: 620px;
      object-fit: cover;
      border-radius: 12px;
      display: block;
      transition: transform 0.45s ease, filter 0.35s ease;
      box-shadow: inset 0 -40px 80px rgba(0,0,0,0.06);
    }

    .photo-overlay {
      position: absolute;
      left: 0; right: 42%; top: 0; bottom: 0;
      background: linear-gradient(90deg, rgba(6,95,70,0.08), rgba(6,95,70,0.02));
      pointer-events: none;
    }

    .login-right {
      flex: 0 0 36%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-box {
      width: 100%;
      max-width: 380px;
      text-align: center;
      background: #ffffff;
      border-radius: 14px;
      padding: 28px 26px;
      box-shadow: 0 12px 30px rgba(2,6,23,0.06);
      border: 1px solid rgba(2,6,23,0.03);
    }

    .logo-image {
      width: 64px;
      height: auto;
      margin-bottom: 10px;
      border-radius: 8px;
      background: rgba(255,255,255,0.9);
      padding: 6px;
      box-shadow: 0 6px 18px rgba(2,6,23,0.06);
    }

    .login-box h2 { color: #064e3b; font-size: 1.45rem; margin-bottom: 0.25rem; font-weight: 800; letter-spacing: 0.2px; }
    .login-box p { color: #334155; margin-bottom: 1rem; font-size: 0.95rem; }

    label { font-weight: 600; display: block; margin-top: 0.8rem; color: #0f172a; text-align: left; font-size: 0.88rem; }

    input { width: 100%; padding: 0.78rem 0.9rem; margin-top: 0.45rem; border-radius: 10px; border: 1px solid rgba(2,6,23,0.08); outline: none; font-size: 0.98rem; background: #fbfdff; }
    input:focus { border-color: rgba(6,95,70,0.9); box-shadow: 0 6px 18px rgba(6,95,70,0.08); }

    .btn-login { margin-top: 1.6rem; width: 100%; padding: 0.92rem; background: linear-gradient(90deg,#0ea5a1 0%, #087f5b 100%); border: none; border-radius: 10px; color: white; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .18s ease, box-shadow .18s ease; box-shadow: 0 8px 24px rgba(8,127,91,0.12); }
    .btn-login:hover { transform: translateY(-3px); box-shadow: 0 20px 44px rgba(8,127,91,0.16); }

    .forgot-wrapper { margin-top: 0.6rem; text-align: left; font-size: 0.88rem; }
    .forgot-wrapper a { color: #0b815a; font-weight: 600; text-decoration: none; }
    .register-wrapper { margin-top: 1.15rem; text-align: center; font-size: 0.92rem; }
    .register-wrapper a { color: #0b815a; font-weight: 700; text-decoration: none; }

    .small-muted { color: #64748b; font-size: 0.85rem; margin-top: 6px; }

    .password-wrapper {
      position: relative;
      margin-top: 0.45rem;
    }

    .password-wrapper input {
      padding-right: 2.5rem;
    }

    .toggle-password {
      position: absolute;
      right: 0.9rem;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      color: #64748b;
      cursor: pointer;
      user-select: none;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 24px;
      height: 24px;
      transition: color 0.3s;
    }

    .toggle-password:hover {
      color: #0b815a;
    }

    @media (max-width: 900px) {
      .login-container { flex-direction: column; padding: 2rem 1rem; }
      .login-left, .login-right { flex: 1 1 100%; }
      .login-image { max-height: 420px; border-radius: 10px; }
      .photo-overlay { display: none; }
      .login-right { padding: 1.25rem; }
      .login-box { padding: 20px; max-width: 100%; }
    }
  </style>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    @media (max-width: 900px) {
      .login-left, .login-right { flex: 1 1 100%; }
      .login-image { max-height: 360px; }
      .login-box { padding: 1rem; }
    }
  </style>

</head>

<body>
  <div class="login-container">

    <!-- Kiri -->
    <div class="login-left">
      <div class="photo-card">
        <img src="{{ asset('image/pulu pulu.jpg') }}" alt="Siswa TKIT" class="login-image">
      </div>
      <div class="photo-overlay"></div>
    </div>

    <!-- Kanan -->
    <div class="login-right">
      <div class="login-box">

        <img src="{{ asset('image/logo putih.jpg') }}" alt="logo Jamilul Mu'minin" class="logo-image">
        <h2>TKIT Jamilul Mu'minin</h2>
        <p>Tahun Ajaran 2025 / 2026</p>

        <!-- FORM -->
        <form action="{{ route('login.post') }}" method="POST">
          @csrf
          <label for="email">Email / No HP</label>
          <input type="text" id="email" name="email" placeholder="Masukkan email atau nomor" required>

          <label for="password">Password</label>
          <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            <span id="togglePassword" class="toggle-password">
              <i class="fas fa-eye"></i>
            </span>
          </div>

          <div class="forgot-wrapper">
            <span>Lupa Kata Sandi?</span>
            <a href="{{ route('password.forgot') }}">Pulihkan di sini</a>
          </div>

          <button type="submit" class="btn-login">Masuk Halaman</button>

          <div class="register-wrapper">
            <span>Tidak punya akun?</span>
            <a href="{{ route('pendaftaran') }}">Daftar di sini</a>
          </div>
        </form>

      </div>
    </div>

  </div>

  <script>
    (function () {
      var passwordInput = document.getElementById('password');
      var toggle = document.getElementById('togglePassword');
      if (!passwordInput || !toggle) return;

      toggle.addEventListener('click', function () {
        var isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        
        // Ganti icon mata
        var icon = toggle.querySelector('i');
        if (icon) {
          if (isHidden) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        }
      });
    })();
  </script>
</body>
</html>