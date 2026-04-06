<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Jetski Rental | Sewa Jetski Premium</title>

    <!-- FONT GOOGLE POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0a1922;
            color: #ffffff;
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* VIDEO BACKGROUND - ocean vibe */
        .bg-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -2;
            object-fit: cover;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,20,40,0.75), rgba(0,40,60,0.65));
            z-index: -1;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            position: relative;
            z-index: 2;
        }

        /* header */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(5, 25, 35, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 255, 255, 0.2);
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
            flex-wrap: wrap;
        }
        
        .logo {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #00d4ff, #0099cc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .logo span {
            color: #00d4ff;
            background: none;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            font-weight: 600;
            color: #ffffff;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        
        .nav-links a:hover {
            color: #00d4ff;
        }
        
        .btn-outline {
            border: 1.5px solid #00d4ff;
            padding: 8px 24px;
            border-radius: 40px;
            font-weight: 700;
            background: transparent;
            color: #00d4ff;
            transition: 0.25s;
        }
        
        .btn-outline:hover {
            background: #00d4ff;
            color: #0a1922;
            border-color: #00d4ff;
        }

        /* hero section */
        .hero {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            padding: 70px 0 80px;
        }
        
        .hero-text {
            flex: 1.2;
        }
        
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(to right, #ffffff, #00d4ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .hero-text p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85);
            margin-bottom: 32px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #0099cc);
            border: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0,212,255,0.3);
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,212,255,0.5);
        }
        
        .hero-media {
            flex: 1;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(0,212,255,0.3);
        }
        
        .hero-media img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 16/9;
            object-fit: cover;
        }
        
        .section-title {
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
            color: #ffffff;
        }
        
        .section-sub {
            text-align: center;
            color: rgba(255,255,255,0.7);
            margin-bottom: 50px;
            font-size: 1rem;
        }
        
        /* jetski cards */
        .jetski-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 70px;
        }
        
        .jetski-card {
            background: rgba(10, 35, 45, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,212,255,0.2);
        }
        
        .jetski-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0,212,255,0.6);
            box-shadow: 0 15px 35px rgba(0,212,255,0.2);
        }
        
        .jetski-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .card-content {
            padding: 22px;
        }
        
        .card-content h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #00d4ff;
        }
        
        .card-content p {
            color: rgba(255,255,255,0.75);
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        
        .specs {
            display: flex;
            gap: 15px;
            margin: 12px 0;
            font-size: 0.8rem;
            color: #7fcdff;
        }
        
        .price-tag {
            font-weight: 800;
            font-size: 1.4rem;
            color: #00d4ff;
            margin: 12px 0;
        }
        
        /* form section */
        .form-section {
            background: rgba(10, 35, 45, 0.8);
            backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 48px 40px;
            margin: 40px 0 80px;
            border: 1px solid rgba(0,212,255,0.3);
        }
        
        .form-section h2 {
            font-size: 2rem;
            margin-bottom: 12px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }
        
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .input-group label {
            font-weight: 600;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        
        .input-group input, 
        .input-group select, 
        .input-group textarea {
            padding: 14px 18px;
            border-radius: 24px;
            border: 1px solid rgba(0,212,255,0.3);
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: 0.2s;
            background: rgba(5, 25, 35, 0.8);
            color: white;
        }
        
        .input-group input:focus, 
        .input-group select:focus, 
        .input-group textarea:focus {
            outline: none;
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0,212,255,0.2);
        }
        
        .input-group input::placeholder,
        .input-group textarea::placeholder {
            color: rgba(255,255,255,0.5);
        }
        
        .full-width {
            grid-column: span 2;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #00d4ff, #0099cc);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Poppins', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-submit:hover {
            transform: scale(0.98);
            opacity: 0.9;
        }
        
        /* info tambahan */
        .info-banner {
            background: rgba(0,212,255,0.15);
            border-radius: 28px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            border: 1px solid rgba(0,212,255,0.3);
        }
        
        .info-banner i {
            font-size: 2rem;
            color: #00d4ff;
            margin-bottom: 10px;
        }
        
        /* footer */
        footer {
            background: rgba(5, 20, 28, 0.95);
            backdrop-filter: blur(8px);
            padding: 48px 0 24px;
            border-top-left-radius: 48px;
            border-top-right-radius: 48px;
            margin-top: 40px;
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 40px;
        }
        
        .footer-col h4 {
            color: #00d4ff;
            margin-bottom: 16px;
        }
        
        .footer-col p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }
        
        .social-icons i {
            font-size: 1.5rem;
            margin-right: 18px;
            cursor: pointer;
            transition: 0.2s;
            color: rgba(255,255,255,0.7);
        }
        
        .social-icons i:hover {
            color: #00d4ff;
        }
        
        hr {
            margin: 32px 0 16px;
            border-color: rgba(0,212,255,0.2);
        }
        
        .toast-msg {
            visibility: hidden;
            min-width: 280px;
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(8px);
            color: #fff;
            text-align: center;
            border-radius: 50px;
            padding: 14px 24px;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 200;
            font-weight: 500;
            opacity: 0;
            transition: 0.2s;
            border: 1px solid #00d4ff;
        }
        
        .toast-msg.show {
            visibility: visible;
            opacity: 1;
        }
        
        @media (max-width: 780px) {
            .container { padding: 0 20px; }
            .navbar { flex-direction: column; gap: 15px; }
            .nav-links { justify-content: center; gap: 1rem; }
            .hero { flex-direction: column; text-align: center; padding: 40px 0; }
            .hero-text h1 { font-size: 2.2rem; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .form-section { padding: 28px 20px; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <!-- VIDEO BACKGROUND - ocean waves -->
    <video autoplay muted loop playsinline class="bg-video">
        <source src="{{ asset('video/bg.mp4') }}" type="video/mp4">
        <source src="{{ asset('video/bg.mp4') }}" type="video/mp4">
    </video>

    <!-- AUDIO BACKGROUND -->
    <audio id="bgAudio" loop style="display: none">
        <source src="{{ asset('audio/background-music.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="overlay"></div>

    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">jetski<span>hani</span></div>
                <div class="nav-links">
                    <a href="#home">Beranda</a>
                    <a href="#jetski">Jetski</a>
                    <a href="#kontak">Kontak</a>
                    <a href="#" class="btn-outline" id="demoBtn">🎬 Preview</a>
                    <button id="audioToggle" class="btn-outline" title="Mainkan musik" style="background: none; border: 1.5px solid #00d4ff; cursor: pointer;">🔊 Musik</button>
                    <a href="{{ route('login') }}" class="btn-outline" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: white; border: none;"><i class="fas fa-user-tie"></i> Admin</a>
                    <a href="{{ route('petugas.login') }}" class="btn-outline" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: white; border: none;"><i class="fas fa-user-check"></i> Petugas</a>
                    <a href="{{ route('customer.login') }}" class="btn-outline" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: white; border: none;"><i class="fas fa-user"></i> Customer</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero section -->
        <div class="container" id="home">
            <div class="hero">
                <div class="hero-text">
                    <h1>Sewa Jetski Premium<br>Jogja Bantul</h1>
                    <p>Nikmati kecepatan dan kebebasan di atas ombak dengan jetski berkualitas tinggi. Tersedia berbagai pilihan unit terbaru dengan kondisi prima dan didampingi instruktur profesional.</p>
                </div>
                <div class="hero-media">
                    <img src="{{ asset('video/jetskiy.jpg') }}" alt="Jetski Action">
                </div>
            </div>
        </div>

        <!-- Koleksi Jetski -->
        <div class="container" id="jetski">
            <h2 class="section-title">⚡ Jetski Premium</h2>
            <p class="section-sub">Unit terawat, performa terbaik, siap meluncur di laut</p>
            <div class="jetski-grid">
                <div class="jetski-grid">
        @foreach($units as $item)
        <div class="jetski-card">
            <img class="jetski-img" 
     src="{{ str_contains($item->gambar, 'http') ? $item->gambar : asset($item->gambar) }}" 
     alt="{{ $item->name }}">
            
            <div class="card-content">
                <h3>{{ $item->name }}</h3>
                <p>{{ $item->deskripsi_promo ?? 'Unit mewah dengan kondisi prima, stabil dan nyaman untuk digunakan.' }}</p>
                
                <div class="specs">
                    <span><i class="fas fa-tachometer-alt"></i> 230 HP</span>
                    <span><i class="fas fa-box"></i> Stok: {{ $item->stok }}</span>
                </div>

                <div class="price-tag">Rp{{ number_format($item->harga, 0, ',', '.') }}/jam</div>
                
                @if($item->diskon_persen > 0)
                    <small style="color:#ff7f7f;">*Diskon {{ (float)$item->diskon_persen }}% tersedia!</small>
                @else
                    <small style="color:#7fcdff;">*termasuk life jacket + instruktur</small>
                @endif
            </div>
        </div>
        @endforeach
    </div>
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="container">
            <div class="info-banner">
                <i class="fas fa-umbrella-beach"></i>
                <h3 style="margin: 10px 0;">Paket Hemat & Promo</h3>
                <p>Sewa 3 jam = gratis 30 menit | Sewa full day (8 jam) = diskon 15% + free snack box</p>
                <p style="margin-top: 10px;"><i class="fas fa-clock"></i> Operasional: 08.00 - 18.00 WIB | Lokasi: Sungai Kali Opak Bantul Bawah Jembatan Kratek 2</p>
            </div>
        </div>


    </main>

    <footer id="kontak">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <h4>Jetski Rental Premium</h4>
                    <p>Sungai Kali Opak Bantul Bawah Jembatan Kratek 2<br>Buka Setiap Hari 08.00 - 18.00</p>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-phone"></i> +62 882 1544 6224</p>
                </div>
                <div class="footer-col">
                    <h4>Ikuti Kami</h4>
                    <div class="social-icons">
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-tiktok"></i>
                        <i class="fab fa-youtube"></i>
                    </div>
                </div>
            </div>
            <hr>
            <p style="text-align: center; font-size: 0.85rem;">&copy; 2025 Jetski Rental — Nikmati Ombaknya, Rasakan Kebebasannya!</p>
        </div>
    </footer>

    <div id="toastMsg" class="toast-msg">✅ Berhasil! Kami akan segera menghubungi Anda.</div>

    <script>
        (function(){
            const navLinks = document.querySelectorAll('.nav-links a');
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    const hash = link.getAttribute('href');
                    if(hash && hash !== '#' && hash.startsWith('#')){
                        e.preventDefault();
                        const targetElem = document.querySelector(hash);
                        if(targetElem){
                            targetElem.scrollIntoView({ behavior: 'smooth' });
                        }
                    } else if(link.id === 'demoBtn'){
                        e.preventDefault();
                        showToast('🏄‍♂️ Cek koleksi jetski & paket promo seru!');
                    }
                });
            });

            function showToast(message) {
                const toast = document.getElementById('toastMsg');
                toast.textContent = message || '✅ Berhasil! Kami akan segera menghubungi Anda.';
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2800);
            }

            // Audio autoplay handler
            const bgAudio = document.getElementById('bgAudio');
            const audioToggle = document.getElementById('audioToggle');
            let isAudioPlaying = false;

            if(bgAudio && audioToggle){
                bgAudio.volume = 0.4; // Set volume ke 40%

                // Toggle audio play/pause
                audioToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    if(isAudioPlaying){
                        bgAudio.pause();
                        audioToggle.textContent = '� Musik';
                        audioToggle.title = 'Mainkan musik';
                        isAudioPlaying = false;
                    } else {
                        bgAudio.play().then(() => {
                            audioToggle.textContent = '🔊 Musik';
                            audioToggle.title = 'Hentikan musik';
                            isAudioPlaying = true;
                        }).catch((err) => {
                            console.error('Audio play error:', err);
                            // Try with muted first
                            bgAudio.muted = true;
                            bgAudio.play().then(() => {
                                setTimeout(() => {
                                    bgAudio.muted = false;
                                }, 500);
                                audioToggle.textContent = '🔊 Musik';
                                audioToggle.title = 'Hentikan musik';
                                isAudioPlaying = true;
                            });
                        });
                    }
                });

                // Try to play audio on first user interaction
                const playOnInteraction = () => {
                    if(!isAudioPlaying){
                        bgAudio.play().then(() => {
                            audioToggle.textContent = '🔊 Musik';
                            audioToggle.title = 'Hentikan musik';
                            isAudioPlaying = true;
                        }).catch(() => {
                            // Silent fail, user can click button manually
                        });
                        
                        document.removeEventListener('click', playOnInteraction);
                        document.removeEventListener('scroll', playOnInteraction);
                        document.removeEventListener('touchstart', playOnInteraction);
                    }
                };

                // Add listeners for user interaction
                document.addEventListener('click', playOnInteraction);
                document.addEventListener('scroll', playOnInteraction);
                document.addEventListener('touchstart', playOnInteraction);

                // Set initial state
                audioToggle.textContent = '🔇 Musik';
                audioToggle.title = 'Mainkan musik';
            }
        })();
    </script>
</body>
</html>