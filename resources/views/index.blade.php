<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKIT Jamilul Mu'minin - Makassar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: white;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-img {
            width: 50px;
            height: 50px;
        }

        .school-info h2 {
            color: #2d6016;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .school-info p {
            color: #666;
            font-size: 0.85rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
        }

        .nav-links a:hover {
            color: #2d6016;
        }

        .nav-links .dropdown {
            position: relative;
        }

        .login-btn {
            background: linear-gradient(135deg, #4CAF50, #FDD835);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #2d6016, #F9A825);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        /* Hero Section */
        .hero {
            margin-top: 80px;
            padding: 4rem 5%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            background: linear-gradient(135deg, #F1F8E9, #FFFDE7);
        }

        .hero-content h1 {
            font-size: 2.5rem;
            color: #2d6016;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .hero-content p strong {
            color: #2d6016;
        }

        .hero-image {
            display: flex;
            justify-content: center;
        }

        .hero-image img {
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* Visi Misi Section */
        .visi-misi {
            padding: 5rem 5%;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.2rem;
            color: #2d6016;
            margin-bottom: 0.5rem;
        }

        .vm-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .vm-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-left: 5px solid #4CAF50;
        }

        .vm-card h3 {
            color: #2d6016;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .vm-card p, .vm-card ol {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }

        .vm-card ol {
            padding-left: 1.2rem;
        }

        .vm-card ol li {
            margin-bottom: 0.8rem;
        }

        /* Kurikulum Section */
        .kurikulum {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #F1F8E9, #FFFDE7);
        }

        .kurikulum-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 1rem 2rem;
            border: none;
            background: white;
            color: #333;
            font-weight: 600;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #4CAF50, #FDD835);
            color: white;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        .kurikulum-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .kurikulum-text h3 {
            color: #2d6016;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .kurikulum-text p {
            color: #555;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .kurikulum-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* Program Section */
        .program {
            padding: 5rem 5%;
            background: white;
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .program-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .program-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .program-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #4CAF50, #FDD835);
            background-size: cover;
            background-position: center;
        }
        
        .program-card:nth-child(1) .program-image {
            background-image: url('{{ asset("image/kegiatan sekolah.jpg") }}');
        }
        
        .program-card:nth-child(2) .program-image {
            background-image: url('{{ asset("image/kegiatan kelas(4).jpg") }}');
        }
        
        .program-card:nth-child(3) .program-image {
            background-image: url('{{ asset("image/pulu pulu.jpg") }}');
        }
        
        .program-card:nth-child(4) .program-image {
            background-image: url('{{ asset("image/perpisahan.jpg") }}');
        }

        .program-content {
            padding: 1.5rem;
        }

        .program-content h4 {
            color: #2d6016;
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
        }

        .program-content p {
            color: #666;
            line-height: 1.6;
        }

        /* Guru Section */
        .guru {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
        }

        .guru .section-header h2 {
            color: white;
        }

        .guru .section-header p {
            color: rgba(255,255,255,0.95);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        .guru-slider {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }

        .guru-track {
            display: flex;
            gap: 2rem;
            padding: 2rem 0;
        }

        .guru-card {
            min-width: 250px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .guru-photo {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #FDD835, #F9A825);
        }

        .guru-info {
            padding: 1.5rem;
            text-align: center;
        }

        .guru-info h4 {
            color: #2d6016;
            margin-bottom: 0.5rem;
        }

        .guru-info p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Fasilitas Section */
        .fasilitas {
            padding: 5rem 5%;
            background: white;
        }

        .fasilitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .fasilitas-card {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #F1F8E9, #FFFDE7);
            border-radius: 15px;
            transition: all 0.3s;
        }

        .fasilitas-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .fasilitas-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #4CAF50, #FDD835);
            border-radius: 10px;
            margin-bottom: 1rem;
            background-size: cover;
            background-position: center;
        }
        
        .fasilitas-card:nth-child(1) .fasilitas-image {
            background-image: url('{{ asset("image/kegiatan sekolah.jpg") }}');
        }
        
        .fasilitas-card:nth-child(2) .fasilitas-image {
            background-image: url('{{ asset("image/ruang kelas(2).jpg") }}');
        }
        
        .fasilitas-card:nth-child(3) .fasilitas-image {
            background-image: url('{{ asset("image/kegiatan kelas(4).jpg") }}');
        }
        
        .fasilitas-card:nth-child(4) .fasilitas-image {
            background-image: url('{{ asset("image/ruang kelas(2).jpg") }}');
        }

        .fasilitas-card h4 {
            color: #2d6016;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: #1a3d0a;
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        footer p {
            color: rgba(255,255,255,0.9);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Login CTA Section */
        .login-cta {
            padding: 4rem 5%;
            background: linear-gradient(135deg, #2d6016, #4CAF50);
            text-align: center;
            color: white;
        }

        .login-cta h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: white;
        }

        .login-cta p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .login-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .login-btn-admin, .login-btn-parent {
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-btn-admin {
            background: white;
            color: #2d6016;
        }

        .login-btn-admin:hover {
            background: #FDD835;
            color: #2d6016;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .login-btn-parent {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }

        .login-btn-parent:hover {
            background: white;
            color: #2d6016;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .nav-links {
                display: none;
            }

            .hero, .vm-container, .kurikulum-content {
                grid-template-columns: 1fr;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .section-header h2 {
                font-size: 1.8rem;
            }
            
            .login-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .login-btn-admin, .login-btn-parent {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo-section">
            <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo TKIT" class="logo-img">
            <div class="school-info">
                <h2>TKIT Jamilul Mu'minin</h2>
                <p>Makassar</p>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#kurikulum">Kurikulum</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#fasilitas">Fasilitas</a></li>
            <li><a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>TKIT<br>JAMILUL MU'MININ</h1>
            <p>Dalam pengembangan pembelajaran, TKIT Jamilul Mu'minin senantiasa mengutamakan pembelajaran yang terpusat pada anak. Dengan acuan <strong>Kurikulum Merdeka dan Kurikulum Khas TKIT Jamilul Mu'minin</strong>, pembelajaran dilaksanakan dengan pendekatan <em>Deep Learning</em> dan <em>Computational Thinking</em>. Melalui pendekatan ini, diharapkan anak-anak memiliki kemampuan memecahkan masalah dengan berpikir yang sistematis, logis, dan efisien.</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('image/pulu pulu.jpg') }}" alt="TKIT Students">
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="visi-misi" id="profile">
        <div class="section-header">
            <h2>Visi & Misi TKIT Jamilul Mu'minin</h2>
        </div>
        <div class="vm-container">
            <div class="vm-card">
                <h3>VISI</h3>
                <p>"Membentuk peserta didik menjadi generasi yang memiliki akhlak mulia, teguh, mandiri, komunikatif, kreatif, dan berwawasan global."</p>
            </div>
            <div class="vm-card">
                <h3>MISI</h3>
                <ol>
                    <li>Meletakkan pondasi aqidah, ibadah dan akhlak</li>
                    <li>Menumbuh kembangkan sikap mental, kekuatan fisik dan ketrampilan motorik sebagai bekal berkembangnya jiwa, raga dan akalnya</li>
                    <li>Membentuk generasi yang mandiri, kreatif dan inovatif</li>
                    <li>Memiliki pondasi nilai-nilai Islam</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Kurikulum Section -->
    <section class="kurikulum" id="kurikulum">
        <div class="section-header">
            <h2>Kurikulum TKIT Jamilul Mu'minin</h2>
            <p>Kurikulum Inovatif yang Dapat Membimbing Siswa Menuju Keunggulan dengan Pendekatan Pembelajaran yang Menyeluruh dan Terdepan</p>
        </div>
        
        <div class="kurikulum-tabs">
            <button class="tab-btn active" onclick="showTab('khas')">Kurikulum Khas TKIT Jamilul Mu'minin</button>
            <button class="tab-btn" onclick="showTab('blended')">Blended Learning</button>
            <button class="tab-btn" onclick="showTab('capaian')">Capaian Pembelajaran</button>
            <button class="tab-btn" onclick="showTab('project')">Project Based Learning</button>
        </div>

        <div id="khas" class="tab-content active">
            <div class="kurikulum-content">
                <div class="kurikulum-text">
                    <h3>Kurikulum Khas TKIT Jamilul Mu'minin</h3>
                    <p>TKIT Jamilul Mu'minin Makassar menerapkan <strong>Kurikulum khas TKIT Jamilul Mu'minin</strong> diantaranya pengenalan aqidah, ibadah - akhlak, dan pengenalan bacaan Al Qur'an serta dasar membaca Al Qur'an.</p>
                </div>
                <div class="kurikulum-image">
                    <img src="{{ asset('image/pemadam.jpg') }}" alt="Kurikulum Khas">
                </div>
            </div>
        </div>

        <div id="blended" class="tab-content">
            <div class="kurikulum-content">
                <div class="kurikulum-text">
                    <h3>Blended Learning</h3>
                    <p>Menggabungkan metode pembelajaran konvensional dengan teknologi digital untuk menciptakan pengalaman belajar yang lebih efektif dan menyenangkan bagi anak-anak.</p>
                </div>
                <div class="kurikulum-image">
                    <img src="{{ asset('image/kegiatan kelas(4).jpg') }}" alt="Blended Learning">
                </div>
            </div>
        </div>

        <div id="capaian" class="tab-content">
            <div class="kurikulum-content">
                <div class="kurikulum-text">
                    <h3>Capaian Pembelajaran</h3>
                    <p>Setiap tahap pembelajaran dirancang dengan target capaian yang jelas untuk memastikan perkembangan optimal anak dalam aspek kognitif, afektif, dan psikomotorik.</p>
                </div>
                <div class="kurikulum-image">
                    <img src="{{ asset('image/pulu pulu.jpg') }}" alt="Capaian">
                </div>
            </div>
        </div>

        <div id="project" class="tab-content">
            <div class="kurikulum-content">
                <div class="kurikulum-text">
                    <h3>Project Based Learning</h3>
                    <p>Pembelajaran berbasis proyek yang mendorong anak untuk belajar melalui eksplorasi, eksperimen, dan penyelesaian masalah secara kreatif dan kolaboratif.</p>
                </div>
                <div class="kurikulum-image">
                    <img src="{{ asset('image/perpisahan.jpg') }}" alt="Project Based">
                </div>
            </div>
        </div>
    </section>

    <!-- Program Section -->
    <section class="program" id="program">
        <div class="section-header">
            <h2>Program TKIT Jamilul Mu'minin</h2>
            <p>Membentuk Siswa Unggul Melalui Pendekatan Holistik dan Beragam dalam Pembelajaran.</p>
        </div>
        <div class="program-grid">
            <div class="program-card">
                <div class="program-image"></div>
                <div class="program-content">
                    <h4>Pembelajaran Al Qur'an</h4>
                    <p>Pembelajaran Al Qur'an meliputi (hafalan surat pilihan dan pengenalan dasar membaca dengan metode UMMI)</p>
                </div>
            </div>
            <div class="program-card">
                <div class="program-image"></div>
                <div class="program-content">
                    <h4>Pembelajaran Bahasa</h4>
                    <p>Pengenalan bahasa Arab dan Inggris sejak dini untuk memperkaya kemampuan komunikasi anak</p>
                </div>
            </div>
            <div class="program-card">
                <div class="program-image"></div>
                <div class="program-content">
                    <h4>Kegiatan Outdoor</h4>
                    <p>Program pembelajaran di luar ruangan untuk mengembangkan motorik dan eksplorasi alam</p>
                </div>
            </div>
            <div class="program-card">
                <div class="program-image"></div>
                <div class="program-content">
                    <h4>Seni & Kreativitas</h4>
                    <p>Pengembangan bakat seni, musik, dan kreativitas melalui berbagai kegiatan menarik</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Guru Section -->
    <section class="guru">
        <div class="section-header">
            <h2>Guru TKIT Jamilul Mu'minin</h2>
            <p>Mengenal Lebih Dekat Tim Guru Berkualitas yang Membimbing Siswa Menuju Sukses.</p>
        </div>
        <div class="guru-slider">
            <div class="guru-track">
                <div class="guru-card">
                    <div class="guru-photo"></div>
                    <div class="guru-info">
                        <h4>Ustadzah Fatimah</h4>
                        <p>Guru Kelas KB</p>
                    </div>
                </div>
                <div class="guru-card">
                    <div class="guru-photo"></div>
                    <div class="guru-info">
                        <h4>Ustadzah Aisyah</h4>
                        <p>Guru Kelas TK A</p>
                    </div>
                </div>
                <div class="guru-card">
                    <div class="guru-photo"></div>
                    <div class="guru-info">
                        <h4>Ustadzah Khadijah</h4>
                        <p>Guru Kelas TK B</p>
                    </div>
                </div>
                <div class="guru-card">
                    <div class="guru-photo"></div>
                    <div class="guru-info">
                        <h4>Ustadzah Maryam</h4>
                        <p>Guru Al Qur'an</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section class="fasilitas" id="fasilitas">
        <div class="section-header">
            <h2>Fasilitas Sekolah</h2>
            <p>Suasana Belajar yang Mendukung dengan Fasilitas Modern yang Memungkinkan Siswa Berkembang Secara Optimal dalam Setiap Aspek.</p>
        </div>
        <div class="fasilitas-grid">
            <div class="fasilitas-card">
                <div class="fasilitas-image"></div>
                <h4>Sudut Baca<br>(Perpustakaan)</h4>
            </div>
            <div class="fasilitas-card">
                <div class="fasilitas-image"></div>
                <h4>Ruang BK</h4>
            </div>
            <div class="fasilitas-card">
                <div class="fasilitas-image"></div>
                <h4>Aula</h4>
            </div>
            <div class="fasilitas-card">
                <div class="fasilitas-image"></div>
                <h4>Ruang Kelas</h4>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <p>&copy; TKIT Jamilul Mu'minin Makassar - Generasi Qur'ani</p>
        <p style="margin-top: 1rem;">Taman Kanak-Kanak Islam Terpadu</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">
            <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.9); text-decoration: underline;">Login</a> | 
            <a href="{{ route('pendaftaran') }}" style="color: rgba(255,255,255,0.9); text-decoration: underline;">Pendaftaran</a>
        </p>
    </footer>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Remove active from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active to clicked button
            event.target.classList.add('active');
        }

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>