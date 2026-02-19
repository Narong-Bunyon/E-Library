<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'E‑Library')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ui-body">
    <header class="topbar">
        <div class="container topbar__inner">
            <a class="brand" href="{{ route('home') }}" aria-label="E-Library home">
                @if(app()->getLocale() === 'kh')
                    <span class="brand__mark" aria-hidden="true">ប</span>
                    <span class="brand__text">បណ្ណ</span>
                @else
                    <span class="brand__mark" aria-hidden="true">E</span>
                    <span class="brand__text">E‑Library</span>
                @endif
            </a>

            <nav class="nav" id="site-nav" aria-label="Main">
                <a class="nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Home</a>
                <a class="nav__link {{ request()->routeIs('browse') ? 'is-active' : '' }}" href="{{ route('browse') }}">Browse</a>
                <a class="nav__link {{ request()->routeIs('categories') ? 'is-active' : '' }}" href="{{ route('categories') }}">Categories</a>
                <a class="nav__link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">About Us</a>

                <div class="nav__right">


                    @php
                        $currentLocale = app()->getLocale(); // 'en' or 'kh'
                    @endphp

                    <div class="lang">
                        <button class="lang__btn" id="langBtn" aria-expanded="false" aria-haspopup="menu" aria-label="Select language">
                            <span class="lang__current">{{ $currentLocale === 'kh' ? 'KH' : 'EN' }}</span>
                            <span class="lang__chev" aria-hidden="true">▾</span>
                        </button>

                        <div class="lang__menu" id="langMenu" role="menu" aria-labelledby="langBtn">
                            <a
                                role="menuitem"
                                class="lang__item {{ $currentLocale === 'en' ? 'is-active' : '' }}"
                                href="{{ route('locale.switch', ['locale' => 'en']) }}"
                            >
                                <span class="lang__code">EN</span>
                                <span class="lang__name">English</span>
                            </a>

                            <a
                                role="menuitem"
                                class="lang__item {{ $currentLocale === 'kh' ? 'is-active' : '' }}"
                                href="{{ route('locale.switch', ['locale' => 'kh']) }}"
                            >
                                <span class="lang__code">KH</span>
                                <span class="lang__name">ខ្មែរ</span>
                            </a>
                        </div>
                    </div>

                    @if (Route::has('login'))
                        <a class="btn btn--ghost" href="{{ route('login') }}">Log In</a>
                    @else
                        <a class="btn btn--ghost is-disabled" href="#" aria-disabled="true" tabindex="-1">Log In</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="btn btn--primary" href="{{ route('register') }}">Log Up</a>
                    @else
                        <a class="btn btn--primary is-disabled" href="#" aria-disabled="true" tabindex="-1">Log Up</a>
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container footer__grid">
            <div>
                <div class="footer__brand">
                    <i class="fas fa-book-open" style="font-size: 1.5rem; color: #6366f1; margin-right: 0.5rem;"></i>
                    <span class="brand__text">E‑Library</span>
                </div>
                <p class="muted mt-2">Access thousands of books, audiobooks, and articles. Learn and grow anytime, anywhere.</p>
            </div>

            <div>
                <div class="footer__title">Quick Links</div>
                <a class="footer__link" href="{{ route('browse') }}">Browse</a>
                <a class="footer__link" href="{{ route('about') }}">About Us</a>
                <a class="footer__link" href="{{ route('categories') }}">Categories</a>
            </div>

            <div>
                <div class="footer__title">Connect With Us</div>
                <div class="social">
                    <a class="social__btn" href="#" aria-label="Facebook" tabindex="-1">f</a>
                    <a class="social__btn" href="#" aria-label="Twitter" tabindex="-1">x</a>
                    <a class="social__btn" href="#" aria-label="YouTube" tabindex="-1">▶</a>
                    <a class="social__btn" href="#" aria-label="LinkedIn" tabindex="-1">in</a>
                    <a class="social__btn" href="#" aria-label="Instagram" tabindex="-1">◎</a>
                </div>
            </div>
        </div>

        <div class="container footer__bottom">
            <div class="muted">© {{ date('Y') }} E‑Library. All rights reserved.</div>
            <div class="footer__meta">
                <a class="footer__link" href="#" tabindex="-1">Privacy Policy</a>
                <a class="footer__link" href="#" tabindex="-1">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>

<script>
    (() => {
        const wrap = document.querySelector('.lang');
        if (!wrap) return;
        
        const btn = document.getElementById('langBtn');
        const menu = document.getElementById('langMenu');
        
        // Add CSS improvements with higher specificity
        const style = document.createElement('style');
        style.textContent = `
            .lang {
                position: relative !important;
            }
            
            .lang__btn {
                position: relative !important;
                z-index: 1001 !important;
            }
            
            .lang__menu {
                position: absolute !important;
                top: 100% !important;
                right: 0 !important;
                min-width: 150px !important;
                background: white !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 8px !important;
                padding: 0.5rem 0 !important;
                z-index: 1000 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                transform: translateY(-10px) !important;
                transition: all 0.3s ease !important;
                display: block !important;
            }
            
            .lang.is-open .lang__menu {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
            
            .lang__item {
                display: block !important;
                padding: 0.75rem 1rem !important;
                border-radius: 6px !important;
                transition: all 0.2s ease !important;
                margin: 0.25rem 0 !important;
                text-decoration: none !important;
                color: #333 !important;
                cursor: pointer !important;
                width: 100% !important;
            }
            
            .lang__item:hover {
                background: #f8fafc !important;
                transform: translateX(4px) !important;
                color: #6366f1 !important;
            }
            
            .lang__item.is-active {
                background: #6366f1 !important;
                color: white !important;
            }
            
            .lang__item.is-active:hover {
                background: #4f46e5 !important;
            }
            
            .lang__code {
                font-weight: 600 !important;
                margin-right: 0.5rem !important;
            }
            
            .lang__name {
                font-size: 0.875rem !important;
            }
        `;
        document.head.appendChild(style);
        
        const toggle = () => {
            const isOpen = wrap.classList.contains('is-open');
            console.log('Toggle dropdown, currently:', isOpen);
            
            if (!isOpen) {
                wrap.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
                console.log('Opening dropdown');
            } else {
                wrap.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
                console.log('Closing dropdown');
            }
        };
        
        const close = () => {
            wrap.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
            console.log('Closing dropdown (outside click)');
        };
        
        // Toggle dropdown when button is clicked
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Language button clicked');
            toggle();
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!wrap.contains(e.target)) {
                close();
            }
        });
        
        // Close dropdown with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                close();
            }
        });
        
        // Debug: Log elements found
        console.log('Lang wrapper:', wrap);
        console.log('Lang button:', btn);
        console.log('Lang menu:', menu);
    })();
</script>

</html>

