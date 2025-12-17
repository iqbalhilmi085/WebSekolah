<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Password Baru - TKIT Jamilul Mu'minin</title>
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

    .password-wrapper {
      position: relative;
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

    .help-text {
      font-size: 0.85rem;
      color: #64748b;
      margin-top: 0.25rem;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <div class="reset-header">
      <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo TKIT">
      <h2>Buat Password Baru</h2>
      <p>Masukkan password baru Anda</p>
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

    <form action="{{ route('password.update') }}" method="POST">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <input type="hidden" name="email" value="{{ $email }}">

      <div class="form-group">
        <label for="password">
          <i class="fas fa-lock"></i> Password Baru
        </label>
        <div class="password-wrapper">
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Masukkan password baru (min. 6 karakter)" 
            required
            autofocus
          >
          <span id="togglePassword" class="toggle-password">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="help-text">Minimal 6 karakter</div>
      </div>

      <div class="form-group">
        <label for="password_confirmation">
          <i class="fas fa-lock"></i> Konfirmasi Password
        </label>
        <div class="password-wrapper">
          <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            placeholder="Ulangi password baru" 
            required
          >
          <span id="togglePasswordConfirmation" class="toggle-password">
            <i class="fas fa-eye"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="btn-reset">
        <i class="fas fa-key"></i> Reset Password
      </button>
    </form>

    <div class="back-link">
      <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left"></i> Kembali ke Login
      </a>
    </div>
  </div>

  <script>
    // Toggle password visibility
    (function () {
      var passwordInput = document.getElementById('password');
      var passwordConfirmationInput = document.getElementById('password_confirmation');
      var togglePassword = document.getElementById('togglePassword');
      var togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');

      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
          var isHidden = passwordInput.type === 'password';
          passwordInput.type = isHidden ? 'text' : 'password';
          
          var icon = togglePassword.querySelector('i');
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
      }

      if (togglePasswordConfirmation && passwordConfirmationInput) {
        togglePasswordConfirmation.addEventListener('click', function () {
          var isHidden = passwordConfirmationInput.type === 'password';
          passwordConfirmationInput.type = isHidden ? 'text' : 'password';
          
          var icon = togglePasswordConfirmation.querySelector('i');
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
      }
    })();
  </script>
</body>
</html>

