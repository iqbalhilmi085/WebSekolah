<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - TKIT Jamilul Mu'minin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #F1F8E9 0%, #FFFDE7 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .registration-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .registration-header {
            text-align: center;
            margin-bottom: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .registration-header img {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        .registration-header h1 {
            color: #064e3b;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .registration-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .registration-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #064e3b;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #064e3b;
        }

        .form-group label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(2,6,23,0.08);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            font-size: 0.95rem;
            line-height: 1.5;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #064e3b;
            box-shadow: 0 0 0 0.2rem rgba(6,78,59,0.15);
            outline: none;
        }

        /* Styling khusus untuk select dropdown - PRESISI BANGET */
        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%2364748b' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 14px;
            padding-right: 3rem !important;
            padding-left: 1rem !important;
            cursor: pointer;
            color: #0f172a !important;
            font-weight: 400;
            font-size: 0.95rem;
            min-height: 45px;
            overflow: visible;
            text-overflow: clip;
            white-space: nowrap;
        }

        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23064e3b' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            color: #0f172a !important;
        }

        select.form-control option {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: #0f172a;
            background-color: white;
            line-height: 1.8;
        }

        select.form-control option:checked {
            background-color: #f0fdf4;
            color: #064e3b;
            font-weight: 600;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0ea5a1 0%, #087f5b 100%);
            border: none;
            border-radius: 10px;
            padding: 0.9rem 2.5rem;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 8px 24px rgba(8,127,91,0.12);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 44px rgba(8,127,91,0.16);
            color: white;
        }

        .btn-back {
            background: #64748b;
            border: none;
            border-radius: 10px;
            padding: 0.9rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #475569;
            color: white;
            text-decoration: none;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1.5rem;
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

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #1e40af;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .info-box i {
            margin-right: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
            display: flex;
            flex-wrap: wrap;
        }

        .form-row > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            box-sizing: border-box;
        }

        /* Memastikan select dropdown tidak terpotong */
        .form-row .form-group {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .form-row .form-group select {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        @media (max-width: 768px) {
            .registration-container {
                padding: 0 0.75rem;
            }

            .registration-card {
                padding: 1.5rem;
            }

            .registration-header {
                padding: 1.5rem;
            }

            .registration-header h1 {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .button-group {
                flex-direction: column;
                width: 100%;
            }

            .button-group .btn {
                width: 100%;
                margin: 0;
            }

            .form-row {
                margin-left: 0;
                margin-right: 0;
            }

            .form-row > [class*="col-"] {
                padding-left: 0;
                padding-right: 0;
                margin-bottom: 1rem;
                width: 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            .form-row > [class*="col-"]:last-child {
                margin-bottom: 0;
            }

            /* Memastikan select dropdown terlihat jelas di mobile */
            select.form-control {
                font-size: 1rem !important;
                padding: 0.85rem 3rem 0.85rem 1rem !important;
                min-height: 48px !important;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem 0;
            }

            .registration-header {
                padding: 1rem;
            }

            .registration-header h1 {
                font-size: 1.3rem;
            }

            .registration-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-header">
            <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo TKIT">
            <h1>Pendaftaran Siswa Baru</h1>
            <p>TKIT Jamilul Mu'minin - Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Data Anak -->
            <div class="registration-card">
                <h3 class="section-title">
                    <i class="fas fa-child"></i> Data Anak
                </h3>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> NIS akan diberikan oleh admin setelah pendaftaran diverifikasi.
                </div>

                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="nama">Nama Lengkap Anak *</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-5">
                        <label for="jenis_kelamin">Jenis Kelamin *</label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" 
                                id="jenis_kelamin" name="jenis_kelamin" required style="width: 100%; min-width: 100%; max-width: 100%;">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tanggal_lahir">Tanggal Lahir *</label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        @error('tanggal_lahir')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="kelas">Kelas yang Dituju *</label>
                        <select class="form-control @error('kelas') is-invalid @enderror" 
                                id="kelas" name="kelas" required style="width: 100%; min-width: 100%; max-width: 100%;">
                            <option value="">-- Pilih Kelas yang Dituju --</option>
                            <option value="TK A" {{ old('kelas') == 'TK A' ? 'selected' : '' }}>TK A</option>
                            <option value="TK B" {{ old('kelas') == 'TK B' ? 'selected' : '' }}>TK B</option>
                        </select>
                        @error('kelas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap *</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto">Foto Anak (Opsional)</label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Format: JPG, PNG. Maksimal 2MB
                    </small>
                    @error('foto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Data Orang Tua -->
            <div class="registration-card">
                <h3 class="section-title">
                    <i class="fas fa-users"></i> Data Orang Tua / Wali
                </h3>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Email dan nomor telepon akan digunakan untuk login ke portal orang tua. Pastikan data yang diisi benar.
                </div>

                <div class="form-group">
                    <label for="nama_orangtua">Nama Lengkap Orang Tua / Wali *</label>
                    <input type="text" class="form-control @error('nama_orangtua') is-invalid @enderror" 
                           id="nama_orangtua" name="nama_orangtua" value="{{ old('nama_orangtua') }}" required>
                    @error('nama_orangtua')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required
                               placeholder="contoh@email.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="no_telp">Nomor Telepon / WhatsApp *</label>
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                               id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required
                               placeholder="081234567890">
                        @error('no_telp')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password *</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required
                                   placeholder="Minimal 6 karakter">
                            <span id="togglePassword" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Konfirmasi Password *</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required
                                   placeholder="Ulangi password">
                            <span id="togglePasswordConfirmation" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="registration-card">
                <div class="button-group">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane"></i> Daftarkan Sekarang
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
                </div>
                <p class="text-center mt-3 mb-0" style="color: #64748b; font-size: 0.85rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" style="color: #0b815a; font-weight: 600;">Login di sini</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
