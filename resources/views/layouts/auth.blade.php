<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Finrai - Track Your Money, Control Your Future')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN fallback for guaranteed rendering + Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* =========================================================================
           RESET & GLOBAL STYLES
           ========================================================================= */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #0A0E1A;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* =========================================================================
           TWO-COLUMN AUTH LAYOUT (Form Column & Image Column)
           ========================================================================= */
        .auth-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            width: 100vw;
        }

        @media (max-width: 900px) {
            .auth-layout {
                grid-template-columns: 1fr;
            }
            .auth-right {
                display: none !important;
            }
        }

        /* =========================================================================
           LEFT PANEL: FORM COLUMN
           ========================================================================= */
        .auth-left {
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.25rem 3.5rem;
            min-height: 100vh;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 640px) {
            .auth-left {
                padding: 1.5rem 1.25rem;
            }
        }

        /* Brand Header */
        .auth-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .auth-brand:hover {
            opacity: 0.9;
        }
        .auth-brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(212, 175, 55, 0.35);
            flex-shrink: 0;
        }
        .auth-brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: #0A0E1A;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }
        .auth-brand-name span {
            color: #D4AF37;
        }
        .auth-brand-tagline {
            font-size: 0.72rem;
            color: #64748B;
            font-weight: 500;
            letter-spacing: 0.01em;
            margin-top: 0.15rem;
        }

        /* Back to Home Link */
        .auth-back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            font-weight: 600;
            color: #64748B;
            text-decoration: none;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            transition: all 0.2s ease;
            margin-bottom: 1.75rem;
            width: fit-content;
        }
        .auth-back-home:hover {
            color: #0F172A;
            border-color: #CBD5E1;
            background: #F1F5F9;
            transform: translateX(-2px);
        }

        /* Form Wrapper & Inner Container */
        .auth-form-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.75rem 0;
        }
        .auth-form-inner {
            width: 100%;
            max-width: 460px;
        }

        /* Page Headers */
        .auth-page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.025em;
            line-height: 1.2;
        }
        .auth-page-subtitle {
            font-size: 0.9rem;
            color: #64748B;
            margin-top: 0.4rem;
            line-height: 1.5;
        }

        /* Form Components with Clean Spacing */
        .auth-form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }
        .auth-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.45rem;
        }
        .auth-label span.req {
            color: #E11D48;
            margin-left: 2px;
        }
        .auth-input-wrap {
            position: relative;
        }
        .auth-input {
            width: 100%;
            height: 48px;
            padding: 0 1.15rem;
            border-radius: 0.875rem;
            border: 1.5px solid #E2E8F0;
            background: #F8FAFC;
            color: #0F172A;
            font-size: 0.925rem;
            font-weight: 500;
            transition: all 0.2s ease;
            outline: none;
            font-family: inherit;
        }
        .auth-input:focus {
            background: #FFFFFF;
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .auth-input::placeholder {
            color: #94A3B8;
            font-weight: 400;
        }
        .auth-input.has-error {
            border-color: #FDA4AF;
            background: #FFF1F2;
        }
        .auth-input.has-icon-right {
            padding-right: 3rem;
        }

        /* Password Eye Toggle */
        .auth-toggle-pwd {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            font-size: 0.95rem;
        }
        .auth-toggle-pwd:hover {
            color: #475569;
        }

        /* 2-Column Inputs Grid (for password & confirm password) */
        .auth-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 540px) {
            .auth-grid-2 {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        /* Remember Me & Forgot Password */
        .auth-flex-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.15rem 0 1.5rem 0;
        }
        .auth-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }
        .auth-checkbox {
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 0.35rem;
            border: 1.5px solid #CBD5E1;
            accent-color: #2563EB;
            cursor: pointer;
        }
        .auth-forgot-link {
            font-size: 0.825rem;
            font-weight: 600;
            color: #2563EB;
            text-decoration: none;
            transition: color 0.2s;
        }
        .auth-forgot-link:hover {
            color: #1D4ED8;
            text-decoration: underline;
        }

        /* Primary Submit Button */
        .auth-btn-primary {
            width: 100%;
            height: 50px;
            border-radius: 9999px;
            background: #2563EB;
            color: #FFFFFF;
            font-size: 0.975rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.28);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .auth-btn-primary:hover {
            background: #1D4ED8;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.38);
        }
        .auth-btn-primary:active {
            transform: translateY(0);
        }

        /* Switch Auth Links Box */
        .auth-switch-box {
            border-top: 1px solid #F1F5F9;
            padding-top: 1.35rem;
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #64748B;
            font-weight: 500;
        }
        .auth-switch-link {
            font-weight: 700;
            color: #2563EB;
            text-decoration: none;
            margin-left: 0.35rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: color 0.2s;
        }
        .auth-switch-link:hover {
            color: #1D4ED8;
            text-decoration: underline;
        }

        /* Error Text */
        .auth-error-text {
            font-size: 0.775rem;
            color: #E11D48;
            font-weight: 500;
            margin-top: 0.35rem;
        }

        /* Alerts */
        .auth-alert-box {
            padding: 0.85rem 1.15rem;
            border-radius: 0.875rem;
            font-size: 0.85rem;
            line-height: 1.45;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.35rem;
        }
        .auth-alert-success {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }
        .auth-alert-danger {
            background: #FFF1F2;
            border: 1px solid #FECDD3;
            color: #9F1239;
        }

        /* Footer */
        .auth-footer-copy {
            font-size: 0.72rem;
            color: #94A3B8;
            font-weight: 500;
            text-align: center;
            padding-top: 1rem;
        }

        /* =========================================================================
           RIGHT PANEL: IMAGE & BRAND SHOWCASE COLUMN
           ========================================================================= */
        .auth-right {
            position: relative;
            overflow: hidden;
            background: #0A0E1A;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3.5rem;
        }
        .auth-right-slider {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .auth-right-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.8s ease-in-out;
        }
        .auth-right-slide.active {
            opacity: 1;
        }
        .auth-right-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 14, 26, 0.45) 0%, rgba(10, 14, 26, 0.25) 35%, rgba(10, 14, 26, 0.92) 100%);
            z-index: 1;
        }
        .auth-right-content {
            position: relative;
            z-index: 2;
            max-width: 520px;
        }

        /* Slide Dots */
        .auth-slide-dots {
            display: flex;
            gap: 0.45rem;
            margin-bottom: 1.25rem;
        }
        .auth-slide-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.35);
            border: none;
            cursor: pointer;
            transition: all 0.35s ease;
        }
        .auth-slide-dot.active {
            width: 26px;
            background: #D4AF37;
        }

        /* Right Showcase Badge & Typography */
        .auth-right-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.9rem;
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.45);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #F0D060;
            margin-bottom: 1rem;
            backdrop-filter: blur(8px);
        }
        .auth-right-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.25rem;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1.15;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }
        .auth-right-title span {
            color: #D4AF37;
        }
        .auth-right-desc {
            font-size: 0.925rem;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        /* Feature Pills */
        .auth-feature-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }
        .auth-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.95rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 9999px;
            font-size: 0.775rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .auth-pill i {
            color: #D4AF37;
            font-size: 0.72rem;
        }
    </style>
</head>
<body>

<div class="auth-layout">

    <!-- =====================================================================
         KOLOM KIRI: FORM PANEL
         ===================================================================== -->
    <div class="auth-left">
        <!-- Brand Header -->
        <div>
            <a href="{{ url('/') }}" class="auth-brand">
                <div class="auth-brand-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div class="auth-brand-name">Fin<span>rai</span></div>
                    <div class="auth-brand-tagline">Track Your Money, Control Your Future.</div>
                </div>
            </a>
        </div>

        <!-- Form Container -->
        <div class="auth-form-wrap">
            <div class="auth-form-inner">
                <a href="{{ url('/') }}" class="auth-back-home">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Beranda</span>
                </a>

                @yield('content')
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="auth-footer-copy">
            &copy; {{ date('Y') }} Finrai Financial App. Hak cipta dilindungi undang-undang.
        </div>
    </div>

    <!-- =====================================================================
         KOLOM KANAN: GAMBAR / SHOWCASE PANEL
         ===================================================================== -->
    <div class="auth-right">
        <!-- Image Slider (Mecca & Madinah) -->
        <div class="auth-right-slider">
            <div class="auth-right-slide active" id="arSlide1" style="background-image: url('{{ asset('images/mecca_bg.jpg') }}')"></div>
            <div class="auth-right-slide" id="arSlide2" style="background-image: url('{{ asset('images/madinah_bg.jpg') }}')"></div>
        </div>

        <!-- Gradient Contrast Overlay -->
        <div class="auth-right-overlay"></div>

        <!-- Content on Image -->
        <div class="auth-right-content">
            <div class="auth-slide-dots">
                <button type="button" class="auth-slide-dot active" id="ard-0" onclick="arGoTo(0)" aria-label="Slide 1"></button>
                <button type="button" class="auth-slide-dot" id="ard-1" onclick="arGoTo(1)" aria-label="Slide 2"></button>
            </div>

            <div class="auth-right-badge">
                <i class="fa-solid fa-star-and-crescent"></i>
                <span>Platform Keuangan Islami</span>
            </div>

            <h1 class="auth-right-title" id="arTitle">
                Masjidil Haram<br><span>Makkah Al-Mukarramah</span>
            </h1>

            <p class="auth-right-desc" id="arDesc">
                Rencanakan keuangan Anda bersama Finrai dan wujudkan impian ibadah Umrah & Haji ke Tanah Suci yang penuh berkah.
            </p>

            <div class="auth-feature-pills">
                <span class="auth-pill"><i class="fa-solid fa-chart-pie"></i> Dashboard Finansial</span>
                <span class="auth-pill"><i class="fa-solid fa-kaaba"></i> Tabungan Umrah & Haji</span>
                <span class="auth-pill"><i class="fa-solid fa-sliders"></i> Batas Anggaran</span>
                <span class="auth-pill"><i class="fa-solid fa-shield-halved"></i> Aman & Terpercaya</span>
            </div>
        </div>
    </div>

</div>

<!-- Interactive Slider Script -->
<script>
    let arCur = 0;
    const arSlides = [document.getElementById('arSlide1'), document.getElementById('arSlide2')];
    const arDots = [document.getElementById('ard-0'), document.getElementById('ard-1')];
    const arTitles = [
        'Masjidil Haram<br><span style="color:#D4AF37">Makkah Al-Mukarramah</span>',
        'Masjid Nabawi<br><span style="color:#D4AF37">Madinah Al-Munawwarah</span>'
    ];
    const arDescs = [
        'Rencanakan keuangan Anda bersama Finrai dan wujudkan impian ibadah Umrah & Haji ke Tanah Suci yang penuh berkah.',
        'Kota Nabi yang dipenuhi cahaya — jadikan perjalanan suci ini sebagai motivasi terbaik dalam mengelola keuangan Anda.'
    ];

    function arGoTo(i) {
        if (!arSlides[0] || !arSlides[1]) return;
        arSlides[arCur].classList.remove('active');
        arDots[arCur].classList.remove('active');
        arCur = i;
        arSlides[arCur].classList.add('active');
        arDots[arCur].classList.add('active');
        document.getElementById('arTitle').innerHTML = arTitles[arCur];
        document.getElementById('arDesc').textContent = arDescs[arCur];
    }

    setInterval(() => {
        arGoTo((arCur + 1) % 2);
    }, 7000);
</script>

@yield('scripts')

</body>
</html>
