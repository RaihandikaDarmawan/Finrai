<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finrai - Kelola Keuangan Umrah & Haji Anda</title>
    <meta name="description" content="Finrai membantu Anda merencanakan dan mengelola keuangan untuk ibadah Umrah dan Haji. Kelola pemasukan, pengeluaran, dan tabungan perjalanan suci Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--gold:#D4AF37;--gold-light:#F0D060;--dark:#0A0E1A;--dark2:#111827}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--dark);color:#fff;overflow-x:hidden}
        .font-outfit{font-family:'Outfit',sans-serif}
        ::-webkit-scrollbar{width:6px}
        ::-webkit-scrollbar-track{background:#0A0E1A}
        ::-webkit-scrollbar-thumb{background:#D4AF37;border-radius:999px}

        /* NAVBAR */
        .navbar{position:fixed;top:0;left:0;right:0;z-index:100;padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;transition:all .4s ease}
        .navbar.scrolled{background:rgba(10,14,26,.93);backdrop-filter:blur(20px);border-bottom:1px solid rgba(212,175,55,.15);box-shadow:0 4px 30px rgba(0,0,0,.4)}
        .navbar-brand{display:flex;align-items:center;gap:.75rem;text-decoration:none}
        .brand-icon{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--gold),#B8860B);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;box-shadow:0 4px 15px rgba(212,175,55,.4)}
        .brand-name{font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:800;color:#fff;letter-spacing:-.02em}
        .brand-name span{color:var(--gold)}
        .nav-links{display:flex;align-items:center;gap:2rem;list-style:none}
        .nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:.9rem;font-weight:500;transition:color .3s}
        .nav-links a:hover{color:var(--gold)}
        .nav-cta{display:flex;align-items:center;gap:.75rem}
        .btn-outline-gold{padding:.5rem 1.25rem;border:1.5px solid var(--gold);border-radius:999px;color:var(--gold);text-decoration:none;font-size:.875rem;font-weight:600;transition:all .3s}
        .btn-outline-gold:hover{background:var(--gold);color:var(--dark)}
        .btn-gold{padding:.55rem 1.4rem;background:linear-gradient(135deg,var(--gold),#B8860B);border-radius:999px;color:var(--dark);text-decoration:none;font-size:.875rem;font-weight:700;transition:all .3s;box-shadow:0 4px 15px rgba(212,175,55,.35)}
        .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(212,175,55,.5)}

        /* HERO */
        .hero{position:relative;min-height:100vh;display:flex;align-items:center;overflow:hidden}
        .hero-bg-slider{position:absolute;inset:0;z-index:0}
        .hero-slide{position:absolute;inset:0;background-size:cover;background-position:center;opacity:0;transition:opacity 1.5s ease-in-out}
        .hero-slide.active{opacity:1}
        .hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(10,14,26,.88) 0%,rgba(10,14,26,.6) 50%,rgba(10,14,26,.78) 100%);z-index:1}
        .hero-content{position:relative;z-index:2;max-width:1200px;margin:0 auto;padding:8rem 2rem 4rem;width:100%}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem 1rem;background:rgba(212,175,55,.15);border:1px solid rgba(212,175,55,.4);border-radius:999px;font-size:.8rem;font-weight:600;color:var(--gold-light);margin-bottom:1.5rem;animation:fadeInUp .8s ease forwards}
        .hero-title{font-family:'Outfit',sans-serif;font-size:clamp(2.5rem,6vw,5rem);font-weight:900;line-height:1.05;letter-spacing:-.03em;margin-bottom:1.5rem;animation:fadeInUp .8s ease .1s forwards;opacity:0}
        .hero-title .gold{color:var(--gold)}
        .hero-title .green{color:#4ade80}
        .hero-subtitle{font-size:clamp(1rem,2vw,1.2rem);color:rgba(255,255,255,.75);max-width:600px;line-height:1.75;margin-bottom:2.5rem;animation:fadeInUp .8s ease .2s forwards;opacity:0}
        .hero-actions{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;animation:fadeInUp .8s ease .3s forwards;opacity:0}
        .btn-hero-primary{display:inline-flex;align-items:center;gap:.6rem;padding:.9rem 2rem;background:linear-gradient(135deg,var(--gold),#B8860B);border-radius:999px;color:var(--dark);text-decoration:none;font-size:1rem;font-weight:700;transition:all .3s;box-shadow:0 8px 30px rgba(212,175,55,.4)}
        .btn-hero-primary:hover{transform:translateY(-3px);box-shadow:0 15px 40px rgba(212,175,55,.55)}
        .btn-hero-secondary{display:inline-flex;align-items:center;gap:.6rem;padding:.9rem 2rem;border:2px solid rgba(255,255,255,.3);border-radius:999px;color:#fff;text-decoration:none;font-size:1rem;font-weight:600;transition:all .3s;backdrop-filter:blur(10px);background:rgba(255,255,255,.08)}
        .btn-hero-secondary:hover{border-color:var(--gold);color:var(--gold);background:rgba(212,175,55,.1)}
        .hero-stats{display:flex;align-items:center;gap:2rem;margin-top:3rem;flex-wrap:wrap;animation:fadeInUp .8s ease .4s forwards;opacity:0}
        .stat-item{text-align:center}
        .stat-num{font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800;color:var(--gold)}
        .stat-label{font-size:.75rem;color:rgba(255,255,255,.55);margin-top:.1rem}
        .stat-divider{width:1px;height:40px;background:rgba(255,255,255,.15)}
        .slide-indicators{position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:.5rem}
        .slide-dot{width:8px;height:8px;border-radius:999px;background:rgba(255,255,255,.3);transition:all .4s;cursor:pointer;border:none}
        .slide-dot.active{width:28px;background:var(--gold)}

        /* SECTIONS */
        .section{padding:6rem 2rem;max-width:1200px;margin:0 auto}
        .section-badge{display:inline-flex;align-items:center;gap:.5rem;padding:.35rem 1rem;background:rgba(212,175,55,.1);border:1px solid rgba(212,175,55,.3);border-radius:999px;font-size:.78rem;font-weight:700;color:var(--gold);text-transform:uppercase;letter-spacing:.05em;margin-bottom:1rem}
        .section-title{font-family:'Outfit',sans-serif;font-size:clamp(1.8rem,4vw,3rem);font-weight:800;line-height:1.15;letter-spacing:-.02em;margin-bottom:1rem;color:#fff}
        .section-title span{color:var(--gold)}
        .section-desc{font-size:1rem;color:rgba(255,255,255,.55);max-width:580px;line-height:1.7}

        /* FEATURES */
        .features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;margin-top:3rem}
        .feature-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:2rem;transition:all .4s;position:relative;overflow:hidden}
        .feature-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);opacity:0;transition:opacity .4s}
        .feature-card:hover{background:rgba(255,255,255,.07);border-color:rgba(212,175,55,.25);transform:translateY(-5px);box-shadow:0 20px 50px rgba(0,0,0,.3)}
        .feature-card:hover::before{opacity:1}
        .feature-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:1.25rem}
        .icon-gold{background:rgba(212,175,55,.15);color:var(--gold)}
        .icon-green{background:rgba(46,155,86,.15);color:#4ade80}
        .icon-blue{background:rgba(59,130,246,.15);color:#60a5fa}
        .icon-purple{background:rgba(139,92,246,.15);color:#a78bfa}
        .icon-rose{background:rgba(244,63,94,.15);color:#fb7185}
        .icon-cyan{background:rgba(6,182,212,.15);color:#22d3ee}
        .feature-title{font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.5rem}
        .feature-desc{font-size:.875rem;color:rgba(255,255,255,.5);line-height:1.65}

        /* HOW IT WORKS */
        .how-section{background:rgba(255,255,255,.02);border-top:1px solid rgba(255,255,255,.06);border-bottom:1px solid rgba(255,255,255,.06);padding:6rem 2rem}
        .how-inner{max-width:1200px;margin:0 auto}
        .steps-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:2rem;margin-top:3rem}
        .step-card{text-align:center;padding:2rem 1.5rem}
        .step-num{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#B8860B);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:800;color:var(--dark);margin:0 auto 1.5rem;box-shadow:0 8px 25px rgba(212,175,55,.35)}
        .step-title{font-family:'Outfit',sans-serif;font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:.5rem}
        .step-desc{font-size:.85rem;color:rgba(255,255,255,.5);line-height:1.6}

        /* DUAL IMAGE */
        .dual-image-section{padding:6rem 2rem;max-width:1200px;margin:0 auto}
        .dual-image-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:3rem}
        .city-card{position:relative;border-radius:24px;overflow:hidden;height:380px}
        .city-card img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
        .city-card:hover img{transform:scale(1.07)}
        .city-card-overlay{position:absolute;inset:0;background:linear-gradient(0deg,rgba(0,0,0,.78) 0%,transparent 60%)}
        .city-card-content{position:absolute;bottom:0;left:0;right:0;padding:2rem}
        .city-label{display:inline-block;padding:.25rem .75rem;background:var(--gold);border-radius:999px;font-size:.7rem;font-weight:700;color:var(--dark);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem}
        .city-name{font-family:'Outfit',sans-serif;font-size:1.8rem;font-weight:800;color:#fff}
        .city-desc{font-size:.85rem;color:rgba(255,255,255,.7);margin-top:.25rem}

        /* CTA */
        .cta-section{position:relative;padding:6rem 2rem;text-align:center;overflow:hidden}
        .cta-bg{position:absolute;inset:0;background:linear-gradient(135deg,rgba(212,175,55,.08) 0%,rgba(27,107,58,.08) 100%);border-top:1px solid rgba(212,175,55,.15);border-bottom:1px solid rgba(212,175,55,.15)}
        .cta-glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(212,175,55,.12) 0%,transparent 70%);pointer-events:none}
        .cta-inner{position:relative;z-index:2;max-width:700px;margin:0 auto}
        .cta-title{font-family:'Outfit',sans-serif;font-size:clamp(2rem,4vw,3.5rem);font-weight:900;line-height:1.1;margin-bottom:1.25rem}
        .cta-desc{font-size:1rem;color:rgba(255,255,255,.6);line-height:1.7;margin-bottom:2.5rem}
        .cta-buttons{display:flex;justify-content:center;gap:1rem;flex-wrap:wrap}
        .btn-cta-primary{display:inline-flex;align-items:center;gap:.75rem;padding:1rem 2.5rem;background:linear-gradient(135deg,var(--gold),#B8860B);border-radius:999px;color:var(--dark);text-decoration:none;font-size:1.05rem;font-weight:800;transition:all .3s;box-shadow:0 10px 35px rgba(212,175,55,.4)}
        .btn-cta-primary:hover{transform:translateY(-3px);box-shadow:0 20px 50px rgba(212,175,55,.55)}
        .btn-cta-secondary{display:inline-flex;align-items:center;gap:.75rem;padding:1rem 2.5rem;border:2px solid rgba(212,175,55,.4);border-radius:999px;color:var(--gold);text-decoration:none;font-size:1.05rem;font-weight:700;transition:all .3s;background:rgba(212,175,55,.05)}
        .btn-cta-secondary:hover{background:rgba(212,175,55,.12);border-color:var(--gold)}

        /* FOOTER */
        .footer{background:rgba(255,255,255,.02);border-top:1px solid rgba(255,255,255,.06);padding:3rem 2rem 2rem;text-align:center}
        .footer-brand{display:flex;align-items:center;justify-content:center;gap:.75rem;margin-bottom:1rem;text-decoration:none}
        .footer-brand-name{font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:800;color:#fff}
        .footer-brand-name span{color:var(--gold)}
        .footer-tagline{font-size:.875rem;color:rgba(255,255,255,.4);margin-bottom:2rem}
        .footer-copy{font-size:.78rem;color:rgba(255,255,255,.25);border-top:1px solid rgba(255,255,255,.06);padding-top:1.5rem;margin-top:1rem}

        /* ANIMATIONS */
        @keyframes fadeInUp{from{opacity:0;transform:translateY(25px)}to{opacity:1;transform:translateY(0)}}
        .reveal{opacity:0;transform:translateY(30px);transition:opacity .7s ease,transform .7s ease}
        .reveal.visible{opacity:1;transform:translateY(0)}

        @media(max-width:768px){
            .nav-links{display:none}
            .navbar{padding:1rem 1.25rem}
            .hero-content{padding:7rem 1.25rem 4rem}
            .dual-image-grid{grid-template-columns:1fr}
            .city-card{height:260px}
            .section{padding:4rem 1.25rem}
            .how-section{padding:4rem 1.25rem}
            .dual-image-section{padding:4rem 1.25rem}
            .cta-section{padding:4rem 1.25rem}
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="mainNavbar">
    <a href="{{ url('/') }}" class="navbar-brand">
        <div class="brand-icon"><i class="fa-solid fa-wallet"></i></div>
        <span class="brand-name">Fin<span>rai</span></span>
    </a>
    <ul class="nav-links">
        <li><a href="#features">Layanan</a></li>
        <li><a href="#how">Cara Kerja</a></li>
        <li><a href="#destination">Destinasi</a></li>
    </ul>
    <div class="nav-cta">
        <a href="{{ route('login') }}" class="btn-outline-gold" id="btn-nav-login">Masuk</a>
        <a href="{{ route('register') }}" class="btn-gold" id="btn-nav-daftar">Daftar Gratis</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
    <div class="hero-bg-slider">
        <div class="hero-slide active" id="slide1" style="background-image:url('{{ asset('images/mecca_bg.jpg') }}')"></div>
        <div class="hero-slide" id="slide2" style="background-image:url('{{ asset('images/madinah_bg.jpg') }}')"></div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-star-and-crescent"></i>
            <span>Platform Keuangan Islami Terpercaya</span>
        </div>
        <h1 class="hero-title font-outfit">
            Rencanakan Perjalanan<br>
            <span class="gold">Suci</span> Anda dengan<br>
            <span class="green">Cerdas & Teratur</span>
        </h1>
        <p class="hero-subtitle">
            Finrai hadir untuk membantu Anda mengelola keuangan secara bijak — mulai dari perencanaan tabungan Umrah & Haji, hingga kontrol anggaran harian yang terstruktur dan transparan.
        </p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-hero-primary" id="btn-hero-daftar">
                <i class="fa-solid fa-user-plus"></i> Daftar Akun Gratis
            </a>
            <a href="{{ route('login') }}" class="btn-hero-secondary" id="btn-hero-login">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Akun
            </a>
        </div>
        <div class="hero-stats">
            <div class="stat-item"><div class="stat-num">10K+</div><div class="stat-label">Pengguna Aktif</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="stat-num">Rp 5M+</div><div class="stat-label">Dana Terkelola</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="stat-num">98%</div><div class="stat-label">Kepuasan Pengguna</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="stat-num">100%</div><div class="stat-label">Gratis Selamanya</div></div>
        </div>
    </div>
    <div class="slide-indicators">
        <button class="slide-dot active" id="dot-0" onclick="goToSlide(0)"></button>
        <button class="slide-dot" id="dot-1" onclick="goToSlide(1)"></button>
    </div>
</section>

<!-- FEATURES -->
<section id="features">
    <div class="section">
        <div class="reveal">
            <span class="section-badge"><i class="fa-solid fa-sparkles"></i> Layanan Kami</span>
            <h2 class="section-title">Semua yang Anda Butuhkan<br>untuk Kelola <span>Keuangan</span></h2>
            <p class="section-desc">Dari pencatatan harian hingga perencanaan ibadah, Finrai menyediakan tools lengkap untuk perjalanan finansial Anda.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon icon-gold"><i class="fa-solid fa-chart-pie"></i></div>
                <div class="feature-title">Dashboard Interaktif</div>
                <div class="feature-desc">Pantau kondisi keuangan Anda secara real-time dengan grafik dan visualisasi data yang mudah dipahami.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon icon-green"><i class="fa-solid fa-arrow-down-to-line"></i></div>
                <div class="feature-title">Catat Pemasukan</div>
                <div class="feature-desc">Rekam setiap sumber penghasilan Anda dengan mudah. Gaji, bisnis, investasi — semua tercatat rapi.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon icon-rose"><i class="fa-solid fa-arrow-up-from-line"></i></div>
                <div class="feature-title">Lacak Pengeluaran</div>
                <div class="feature-desc">Kategorikan pengeluaran harian Anda dan ketahui ke mana uang Anda pergi setiap harinya.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon icon-blue"><i class="fa-solid fa-sliders"></i></div>
                <div class="feature-title">Kelola Anggaran</div>
                <div class="feature-desc">Tetapkan batas anggaran per kategori dan dapatkan peringatan ketika Anda mendekati batas yang ditetapkan.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon icon-purple"><i class="fa-solid fa-piggy-bank"></i></div>
                <div class="feature-title">Target Tabungan Umrah & Haji</div>
                <div class="feature-desc">Buat target tabungan khusus untuk perjalanan suci Anda. Pantau progres dan capai tujuan lebih cepat.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon icon-cyan"><i class="fa-solid fa-list-check"></i></div>
                <div class="feature-title">Riwayat Transaksi</div>
                <div class="feature-desc">Lihat semua riwayat transaksi dalam satu tempat. Filter, cari, dan ekspor data dengan mudah.</div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
    <div class="how-inner">
        <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto">
            <span class="section-badge"><i class="fa-solid fa-route"></i> Cara Kerja</span>
            <h2 class="section-title">Mulai dalam <span>3 Langkah</span> Mudah</h2>
            <p class="section-desc" style="margin:0 auto">Bergabung dan kelola keuangan Anda hanya dalam hitungan menit tanpa biaya apapun.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card reveal">
                <div class="step-num">1</div>
                <div class="step-title">Daftar Akun</div>
                <div class="step-desc">Buat akun gratis hanya dengan nama, email, dan password. Tidak perlu kartu kredit atau biaya apapun.</div>
            </div>
            <div class="step-card reveal">
                <div class="step-num">2</div>
                <div class="step-title">Atur Keuangan</div>
                <div class="step-desc">Catat pemasukan, pengeluaran, dan buat target tabungan sesuai kebutuhan dan tujuan finansial Anda.</div>
            </div>
            <div class="step-card reveal">
                <div class="step-num">3</div>
                <div class="step-title">Capai Tujuan</div>
                <div class="step-desc">Pantau progres keuangan Anda secara konsisten dan wujudkan impian perjalanan suci ke Mekah dan Madinah.</div>
            </div>
        </div>
    </div>
</section>

<!-- DUAL IMAGE (Mekah & Madinah) -->
<section id="destination">
    <div class="dual-image-section">
        <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 3rem">
            <span class="section-badge"><i class="fa-solid fa-star-and-crescent"></i> Destinasi Suci</span>
            <h2 class="section-title">Wujudkan Impian ke<br><span>Tanah Haram</span></h2>
            <p class="section-desc" style="margin:0 auto">Rencanakan keuangan Anda bersama Finrai dan raih impian untuk mengunjungi dua kota suci yang paling mulia.</p>
        </div>
        <div class="dual-image-grid reveal">
            <div class="city-card">
                <img src="{{ asset('images/mecca_bg.jpg') }}" alt="Makkah Al-Mukarramah - Masjidil Haram">
                <div class="city-card-overlay"></div>
                <div class="city-card-content">
                    <span class="city-label">Makkah Al-Mukarramah</span>
                    <div class="city-name">Masjidil Haram</div>
                    <div class="city-desc">Rumah Allah dan Kiblat Umat Muslim Dunia</div>
                </div>
            </div>
            <div class="city-card">
                <img src="{{ asset('images/madinah_bg.jpg') }}" alt="Madinah Al-Munawwarah - Masjid Nabawi">
                <div class="city-card-overlay"></div>
                <div class="city-card-content">
                    <span class="city-label">Madinah Al-Munawwarah</span>
                    <div class="city-name">Masjid Nabawi</div>
                    <div class="city-desc">Kota Nabi dan Tempat Peristirahatan Rasulullah</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="cta-glow"></div>
    <div class="cta-inner reveal">
        <span class="section-badge" style="margin-bottom:1.25rem"><i class="fa-solid fa-rocket"></i> Mulai Sekarang</span>
        <h2 class="cta-title font-outfit">
            Wujudkan <span style="color:var(--gold)">Impian Suci</span><br>Dimulai dari Sini
        </h2>
        <p class="cta-desc">Bergabunglah bersama ribuan pengguna yang telah mempercayakan perencanaan keuangan mereka kepada Finrai. Gratis, mudah, dan aman.</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-cta-primary" id="btn-cta-daftar">
                <i class="fa-solid fa-user-plus"></i> Daftar Sekarang — Gratis!
            </a>
            <a href="{{ route('login') }}" class="btn-cta-secondary" id="btn-cta-login">
                <i class="fa-solid fa-right-to-bracket"></i> Sudah Punya Akun?
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <a href="{{ url('/') }}" class="footer-brand">
        <div class="brand-icon" style="width:36px;height:36px;font-size:1rem"><i class="fa-solid fa-wallet"></i></div>
        <span class="footer-brand-name">Fin<span>rai</span></span>
    </a>
    <p class="footer-tagline">Platform Manajemen Keuangan Islami — Track Your Money, Control Your Future.</p>
    <div class="footer-copy">&copy; {{ date('Y') }} Finrai Financial App. Dibuat dengan hati untuk umat Muslim Indonesia.</div>
</footer>

<script>
    // Navbar scroll
    const navbar=document.getElementById('mainNavbar');
    window.addEventListener('scroll',()=>navbar.classList.toggle('scrolled',window.scrollY>30));

    // Hero slider
    let cur=0;
    const slides=[document.getElementById('slide1'),document.getElementById('slide2')];
    const dots=[document.getElementById('dot-0'),document.getElementById('dot-1')];
    function goToSlide(i){slides[cur].classList.remove('active');dots[cur].classList.remove('active');cur=i;slides[cur].classList.add('active');dots[cur].classList.add('active')}
    setInterval(()=>goToSlide((cur+1)%2),6000);

    // Scroll reveal
    const obs=new IntersectionObserver((entries)=>{
        entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*80);obs.unobserve(e.target)}});
    },{threshold:.12});
    document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
</script>

</body>
</html>
