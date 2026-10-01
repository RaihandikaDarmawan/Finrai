<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finrai - Kelola Keuangan Anda')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *{box-sizing:border-box}
        :root{
            --sidebar-w:260px;
            --header-h:64px;
            --gold:#D4AF37;
            --dark:#0F172A;
            --dark2:#1E293B;
        }
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;min-height:100vh;color:#1e293b}
        .font-outfit{font-family:'Outfit',sans-serif}
        ::-webkit-scrollbar{width:5px;height:5px}
        ::-webkit-scrollbar-track{background:transparent}
        ::-webkit-scrollbar-thumb{background:rgba(148,163,184,.4);border-radius:999px}
        ::-webkit-scrollbar-thumb:hover{background:rgba(148,163,184,.7)}

        /* ============================
           SIDEBAR
        ============================ */
        #main-sidebar{
            position:fixed;top:0;left:0;z-index:50;
            height:100vh;width:var(--sidebar-w);
            background:linear-gradient(180deg,#0F172A 0%,#1E293B 100%);
            display:flex;flex-direction:column;
            transition:transform .3s cubic-bezier(.4,0,.2,1);
            box-shadow:4px 0 30px rgba(0,0,0,.25);
        }
        #main-sidebar.sidebar-hidden{transform:translateX(calc(-1 * var(--sidebar-w)))}

        /* Sidebar Brand */
        .sidebar-brand{
            height:var(--header-h);
            padding:0 1.25rem;
            display:flex;align-items:center;justify-content:space-between;
            border-bottom:1px solid rgba(255,255,255,.06);
            flex-shrink:0;
        }
        .sidebar-brand-link{display:flex;align-items:center;gap:.65rem;text-decoration:none}
        .sidebar-brand-icon{
            width:38px;height:38px;border-radius:10px;
            background:linear-gradient(135deg,var(--gold),#B8860B);
            display:flex;align-items:center;justify-content:center;
            font-size:1rem;color:#fff;
            box-shadow:0 4px 12px rgba(212,175,55,.35);
            flex-shrink:0;
        }
        .sidebar-brand-name{font-family:'Outfit',sans-serif;font-size:1.35rem;font-weight:800;color:#fff;letter-spacing:-.02em}
        .sidebar-brand-name span{color:var(--gold)}
        .sidebar-close-btn{
            display:none;
            width:32px;height:32px;border-radius:8px;
            background:rgba(255,255,255,.08);
            border:none;color:rgba(255,255,255,.6);
            cursor:pointer;align-items:center;justify-content:center;
            font-size:1rem;transition:all .2s;
        }
        .sidebar-close-btn:hover{background:rgba(255,255,255,.15);color:#fff}

        /* Sidebar Nav */
        .sidebar-nav{flex:1;overflow-y:auto;padding:1rem .75rem;scrollbar-width:none}
        .sidebar-nav::-webkit-scrollbar{display:none}
        .sidebar-section-label{
            font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
            color:rgba(255,255,255,.3);padding:.5rem .75rem .5rem;margin-top:.5rem;
        }
        .sidebar-link{
            display:flex;align-items:center;gap:.75rem;
            padding:.65rem .75rem;border-radius:12px;
            text-decoration:none;font-size:.875rem;font-weight:600;
            color:rgba(255,255,255,.55);
            transition:all .25s;
            margin-bottom:.2rem;
            position:relative;
        }
        .sidebar-link:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.9)}
        .sidebar-link.active-dashboard{background:linear-gradient(135deg,rgba(212,175,55,.25),rgba(184,134,11,.15));color:var(--gold);border:1px solid rgba(212,175,55,.2)}
        .sidebar-link.active-income{background:linear-gradient(135deg,rgba(34,197,94,.2),rgba(16,185,129,.1));color:#4ade80;border:1px solid rgba(34,197,94,.2)}
        .sidebar-link.active-expense{background:linear-gradient(135deg,rgba(251,113,133,.2),rgba(239,68,68,.1));color:#fb7185;border:1px solid rgba(251,113,133,.2)}
        .sidebar-link.active-budget{background:linear-gradient(135deg,rgba(251,191,36,.2),rgba(245,158,11,.1));color:#fbbf24;border:1px solid rgba(251,191,36,.2)}
        .sidebar-link.active-savings{background:linear-gradient(135deg,rgba(167,139,250,.2),rgba(139,92,246,.1));color:#a78bfa;border:1px solid rgba(167,139,250,.2)}
        .sidebar-link.active-transactions{background:linear-gradient(135deg,rgba(96,165,250,.2),rgba(59,130,246,.1));color:#60a5fa;border:1px solid rgba(96,165,250,.2)}
        .sidebar-link-icon{
            width:32px;height:32px;border-radius:8px;
            display:flex;align-items:center;justify-content:center;
            font-size:.85rem;flex-shrink:0;
            background:rgba(255,255,255,.07);
            transition:all .25s;
        }
        .sidebar-link:hover .sidebar-link-icon{background:rgba(255,255,255,.12)}
        .sidebar-link.active-dashboard .sidebar-link-icon{background:rgba(212,175,55,.25);color:var(--gold)}
        .sidebar-link.active-income .sidebar-link-icon{background:rgba(34,197,94,.2);color:#4ade80}
        .sidebar-link.active-expense .sidebar-link-icon{background:rgba(251,113,133,.2);color:#fb7185}
        .sidebar-link.active-budget .sidebar-link-icon{background:rgba(251,191,36,.2);color:#fbbf24}
        .sidebar-link.active-savings .sidebar-link-icon{background:rgba(167,139,250,.2);color:#a78bfa}
        .sidebar-link.active-transactions .sidebar-link-icon{background:rgba(96,165,250,.2);color:#60a5fa}

        /* Sidebar User Card */
        .sidebar-user{
            padding:.75rem;
            border-top:1px solid rgba(255,255,255,.06);
            flex-shrink:0;
        }
        .sidebar-user-card{
            display:flex;align-items:center;gap:.65rem;
            padding:.65rem .75rem;border-radius:12px;
            background:rgba(255,255,255,.05);
            border:1px solid rgba(255,255,255,.07);
        }
        .sidebar-user-avatar{
            width:36px;height:36px;border-radius:10px;
            background:linear-gradient(135deg,var(--gold),#B8860B);
            color:#0F172A;font-weight:800;font-size:.875rem;
            display:flex;align-items:center;justify-content:center;
            font-family:'Outfit',sans-serif;
            flex-shrink:0;
        }
        .sidebar-user-info{flex:1;min-width:0}
        .sidebar-user-name{font-size:.8rem;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sidebar-user-email{font-size:.65rem;color:rgba(255,255,255,.35);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:.1rem}
        .sidebar-logout-btn{
            width:30px;height:30px;border-radius:8px;
            background:rgba(251,113,133,.12);border:1px solid rgba(251,113,133,.2);
            color:#fb7185;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            font-size:.75rem;transition:all .2s;flex-shrink:0;
        }
        .sidebar-logout-btn:hover{background:rgba(251,113,133,.2)}

        /* ============================
           BACKDROP
        ============================ */
        #sidebar-backdrop{
            position:fixed;inset:0;background:rgba(15,23,42,.65);
            backdrop-filter:blur(3px);z-index:40;
            display:none;transition:opacity .3s;
        }
        #sidebar-backdrop.active{display:block}

        /* ============================
           MAIN WRAPPER
        ============================ */
        #main-wrapper{
            min-height:100vh;
            display:flex;flex-direction:column;
            transition:padding-left .3s cubic-bezier(.4,0,.2,1);
            padding-left:var(--sidebar-w);
        }
        #main-wrapper.sidebar-collapsed{padding-left:0}

        /* ============================
           HEADER
        ============================ */
        #main-header{
            position:sticky;top:0;z-index:30;
            height:var(--header-h);
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(20px);
            border-bottom:1px solid rgba(226,232,240,.8);
            box-shadow:0 1px 20px rgba(0,0,0,.06);
            display:flex;align-items:center;
            padding:0 1.5rem;gap:1rem;
        }
        /* Hamburger button */
        #hamburger-btn{
            width:40px;height:40px;border-radius:10px;
            background:#F1F5F9;border:1px solid #E2E8F0;
            color:#475569;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            font-size:1rem;transition:all .2s;flex-shrink:0;
        }
        #hamburger-btn:hover{background:#E2E8F0;color:#1e293b}
        #hamburger-btn.sidebar-open{background:linear-gradient(135deg,var(--gold),#B8860B);border-color:transparent;color:#fff;box-shadow:0 4px 12px rgba(212,175,55,.35)}

        /* Header search */
        .header-search{flex:1;max-width:400px;margin-left:.5rem}
        .header-search-form{position:relative}
        .header-search-icon{position:absolute;left:.875rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.8rem;pointer-events:none}
        .header-search-input{
            width:100%;padding:.6rem 1rem .6rem 2.4rem;
            border-radius:999px;border:1.5px solid #E2E8F0;
            background:#F8FAFC;font-size:.8rem;color:#1e293b;
            outline:none;transition:all .25s;font-family:'Plus Jakarta Sans',sans-serif;
        }
        .header-search-input:focus{background:#fff;border-color:#D4AF37;box-shadow:0 0 0 3px rgba(212,175,55,.1)}
        .header-search-input::placeholder{color:#94a3b8}

        /* Header actions right */
        .header-actions{display:flex;align-items:center;gap:.6rem;margin-left:auto}
        .header-action-btn{
            width:36px;height:36px;border-radius:999px;
            background:#F1F5F9;border:1px solid #E2E8F0;
            color:#64748b;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            font-size:.8rem;transition:all .2s;
            position:relative;
        }
        .header-action-btn:hover{background:#E2E8F0;color:#1e293b}
        .notif-badge{
            position:absolute;top:-3px;right:-3px;
            width:16px;height:16px;border-radius:999px;
            background:#f43f5e;color:#fff;font-size:.6rem;font-weight:700;
            display:flex;align-items:center;justify-content:center;
            border:2px solid #fff;
        }
        .header-user-pill{
            display:flex;align-items:center;gap:.5rem;
            padding:.35rem .75rem .35rem .4rem;
            background:#F1F5F9;border:1px solid #E2E8F0;
            border-radius:999px;
        }
        .header-user-avatar{
            width:26px;height:26px;border-radius:999px;
            background:linear-gradient(135deg,var(--gold),#B8860B);
            color:#0F172A;font-weight:800;font-size:.7rem;
            display:flex;align-items:center;justify-content:center;
            font-family:'Outfit',sans-serif;flex-shrink:0;
        }
        .header-user-name{font-size:.78rem;font-weight:700;color:#1e293b}

        /* ============================
           PAGE CONTENT
        ============================ */
        #page-content{flex:1;padding:2rem 2rem;max-width:1400px;width:100%;margin:0 auto}
        @media(max-width:1280px){#page-content{max-width:100%}}

        /* ============================
           TOAST
        ============================ */
        .toast-success{position:fixed;bottom:1.5rem;right:1.5rem;z-index:100;background:#0F172A;color:#fff;padding:1rem 1.25rem;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.3);display:flex;align-items:center;gap:.75rem;border:1px solid rgba(255,255,255,.08);animation:toastIn .4s ease}
        .toast-error{position:fixed;bottom:1.5rem;right:1.5rem;z-index:100;background:#450a0a;color:#fecaca;padding:1rem 1.25rem;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.3);display:flex;align-items:center;gap:.75rem;border:1px solid rgba(251,113,133,.2);animation:toastIn .4s ease}
        @keyframes toastIn{from{opacity:0;transform:translateY(20px) scale(.95)}to{opacity:1;transform:translateY(0) scale(1)}}
        .toast-icon-success{width:28px;height:28px;border-radius:999px;background:rgba(74,222,128,.15);color:#4ade80;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0}
        .toast-icon-error{width:28px;height:28px;border-radius:999px;background:rgba(251,113,133,.15);color:#fb7185;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0}
        .toast-close{background:none;border:none;cursor:pointer;margin-left:.5rem;opacity:.5;transition:opacity .2s;font-size:.875rem;color:inherit}
        .toast-close:hover{opacity:1}

        /* ============================
           FOOTER
        ============================ */
        #main-footer{
            background:#fff;border-top:1px solid #E2E8F0;
            padding:1.25rem 2rem;text-align:center;
            font-size:.75rem;color:#94a3b8;font-weight:500;
        }

        /* ============================
           MOBILE responsive
        ============================ */
        @media(max-width:768px){
            #main-wrapper{padding-left:0}
            #main-sidebar{transform:translateX(calc(-1 * var(--sidebar-w)))}
            #main-sidebar.sidebar-mobile-open{transform:translateX(0)}
            .sidebar-close-btn{display:flex!important}
            #hamburger-btn.sidebar-open{background:linear-gradient(135deg,var(--gold),#B8860B);color:#fff}
            #page-content{padding:1.25rem 1rem}
            .header-search{display:none}
            #main-header{padding:0 1rem}
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Backdrop -->
    <div id="sidebar-backdrop" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="main-sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="sidebar-brand-link">
                <div class="sidebar-brand-icon"><i class="fa-solid fa-wallet"></i></div>
                <span class="sidebar-brand-name">Fin<span>rai</span></span>
            </a>
            <button class="sidebar-close-btn" onclick="closeSidebar()" id="sidebar-close-btn" title="Tutup Menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Main Menu</div>

            <a href="{{ route('dashboard') }}" id="nav-dashboard"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active-dashboard' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-chart-pie"></i></div>
                <span>Dashboard</span>
                @if(request()->routeIs('dashboard'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>

            <a href="{{ route('transactions.index') }}" id="nav-transactions"
               class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active-transactions' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-list-check"></i></div>
                <span>Transactions</span>
                @if(request()->routeIs('transactions.*'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>

            <a href="{{ route('income.index') }}" id="nav-income"
               class="sidebar-link {{ request()->routeIs('income.*') ? 'active-income' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <span>Income</span>
                @if(request()->routeIs('income.*'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>

            <a href="{{ route('expenses.index') }}" id="nav-expenses"
               class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active-expense' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-arrow-trend-down"></i></div>
                <span>Expenses</span>
                @if(request()->routeIs('expenses.*'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>

            <a href="{{ route('budget.index') }}" id="nav-budget"
               class="sidebar-link {{ request()->routeIs('budget.*') ? 'active-budget' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-sliders"></i></div>
                <span>Budget</span>
                @if(request()->routeIs('budget.*'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>

            <a href="{{ route('savings.index') }}" id="nav-savings"
               class="sidebar-link {{ request()->routeIs('savings.*') ? 'active-savings' : '' }}">
                <div class="sidebar-link-icon"><i class="fa-solid fa-piggy-bank"></i></div>
                <span>Saving Goals</span>
                @if(request()->routeIs('savings.*'))
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                @endif
            </a>
        </nav>

        <!-- User Card -->
        <div class="sidebar-user">
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Raihan' }}</div>
                    <div class="sidebar-user-email">{{ Auth::user()->email ?? 'user@finrai.id' }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div id="main-wrapper">
        <!-- HEADER -->
        <header id="main-header">
            <!-- Hamburger -->
            <button id="hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                <i class="fa-solid fa-bars" id="hamburger-icon"></i>
            </button>

            <!-- Search (desktop only) -->
            <div class="header-search hidden md:block">
                <form action="{{ route('transactions.index') }}" method="GET" class="header-search-form">
                    <i class="fa-solid fa-magnifying-glass header-search-icon"></i>
                    <input type="text" name="search" placeholder="Cari transaksi..." value="{{ request('search') }}" class="header-search-input">
                </form>
            </div>

            <!-- Right actions -->
            <div class="header-actions">
                <button class="header-action-btn" title="Toggle Theme">
                    <i class="fa-regular fa-sun"></i>
                </button>
                <div style="position:relative">
                    <button class="header-action-btn" title="Notifikasi">
                        <i class="fa-regular fa-bell"></i>
                    </button>
                    <span class="notif-badge">4</span>
                </div>
                <div class="header-user-pill hidden sm:flex">
                    <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 1)) }}</div>
                    <span class="header-user-name">{{ Auth::user()->name ?? 'Raihan' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="header-action-btn" title="Keluar" style="color:#f43f5e;background:rgba(244,63,94,.08);border-color:rgba(244,63,94,.15)">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- TOASTS -->
        @if(session('success'))
            <div class="toast-success" id="toast-success">
                <div class="toast-icon-success"><i class="fa-solid fa-check"></i></div>
                <span style="font-size:.875rem;font-weight:500">{{ session('success') }}</span>
                <button class="toast-close" onclick="document.getElementById('toast-success').remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="toast-error" id="toast-error">
                <div class="toast-icon-error"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div style="font-size:.875rem">
                    <p style="font-weight:700;margin-bottom:.25rem">Terjadi kesalahan:</p>
                    <ul style="list-style:disc;padding-left:1rem;font-size:.8rem;opacity:.8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="toast-close" onclick="document.getElementById('toast-error').remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- MAIN CONTENT -->
        <main id="page-content">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer id="main-footer">
            &copy; {{ date('Y') }} Finrai Financial App — Built with precision for your financial health.
        </footer>
    </div>

    <script>
        // ============================
        // SIDEBAR STATE MANAGEMENT
        // ============================
        const STORAGE_KEY = 'finrai_sidebar_v2';
        let isMobile = () => window.innerWidth < 768;

        function getSavedState() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved !== null) return saved === 'true';
            return !isMobile(); // default: open on desktop, closed on mobile
        }

        function applySidebarState(isOpen) {
            const sidebar = document.getElementById('main-sidebar');
            const wrapper = document.getElementById('main-wrapper');
            const backdrop = document.getElementById('sidebar-backdrop');
            const hamburgerBtn = document.getElementById('hamburger-btn');

            if (!sidebar) return;

            if (isMobile()) {
                // MOBILE: overlay mode
                wrapper.classList.remove('sidebar-collapsed');
                if (isOpen) {
                    sidebar.classList.add('sidebar-mobile-open');
                    sidebar.classList.remove('sidebar-hidden');
                    backdrop.classList.add('active');
                } else {
                    sidebar.classList.remove('sidebar-mobile-open');
                    backdrop.classList.remove('active');
                }
            } else {
                // DESKTOP: push mode
                backdrop.classList.remove('active');
                sidebar.classList.remove('sidebar-mobile-open');
                if (isOpen) {
                    sidebar.classList.remove('sidebar-hidden');
                    wrapper.classList.remove('sidebar-collapsed');
                } else {
                    sidebar.classList.add('sidebar-hidden');
                    wrapper.classList.add('sidebar-collapsed');
                }
            }

            // Update hamburger button style
            if (isOpen) {
                hamburgerBtn.classList.add('sidebar-open');
            } else {
                hamburgerBtn.classList.remove('sidebar-open');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            let currentlyOpen;
            if (isMobile()) {
                currentlyOpen = sidebar.classList.contains('sidebar-mobile-open');
            } else {
                currentlyOpen = !sidebar.classList.contains('sidebar-hidden');
            }
            const newState = !currentlyOpen;
            localStorage.setItem(STORAGE_KEY, newState);
            applySidebarState(newState);
        }

        function closeSidebar() {
            localStorage.setItem(STORAGE_KEY, false);
            applySidebarState(false);
        }

        // Init on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            applySidebarState(getSavedState());
        });

        // Reapply on resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => applySidebarState(getSavedState()), 100);
        });

        // Auto-dismiss toasts after 5s
        setTimeout(() => {
            const ts = document.getElementById('toast-success');
            const te = document.getElementById('toast-error');
            if (ts) ts.style.opacity = '0', setTimeout(() => ts.remove(), 400);
            if (te) te.style.opacity = '0', setTimeout(() => te.remove(), 400);
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
