<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'User - E-Library')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f8fafc;
            --header-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }

        .user-layout {
            display: flex;
            min-height: 100vh;
            background: var(--light-color);
        }

        .user-sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .user-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .user-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .user-header p {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .user-nav {
            flex: 1;
            padding: 1.5rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--secondary-color);
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .user-main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
        }

        .user-header-bar {
            background: var(--header-bg);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: var(--light-color);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary-color);
            font-weight: 500;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .logout-btn:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-1px);
        }

        .user-content {
            flex: 1;
            padding: 2rem;
            background: var(--light-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary-color);
            font-weight: 500;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .section-body {
            padding: 1.5rem;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .book-card {
            background: var(--light-color);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .book-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .book-author {
            font-size: 0.8rem;
            color: var(--secondary-color);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .user-sidebar.show {
                transform: translateX(0);
            }

            .user-main {
                margin-left: 0;
            }

            .user-content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .mobile-menu-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="user-layout">
        <!-- Sidebar -->
        <aside class="user-sidebar" id="userSidebar">
            <div class="user-header">
                <h3><i class="fas fa-book-open me-2"></i>E-Library</h3>
                <p>Your Digital Library</p>
            </div>
            
            <nav class="user-nav">
                <div class="nav-section">
                    <div class="nav-title">Main</div>
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <a href="{{ route('user.library') }}" class="nav-link {{ request()->routeIs('user.library') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        Library
                    </a>
                    <a href="{{ route('browse') }}" class="nav-link {{ request()->routeIs('browse') ? 'active' : '' }}">
                        <i class="fas fa-search"></i>
                        Browse Books
                    </a>
                    <a href="{{ route('categories') }}" class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        Categories
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-title">My Reading</div>
                    <a href="{{ route('user.favorites') }}" class="nav-link {{ request()->routeIs('user.favorites') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i>
                        Favorites
                    </a>
                    <a href="{{ route('user.reading-history') }}" class="nav-link {{ request()->routeIs('user.reading-history') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        Reading History
                    </a>
                    <a href="{{ route('user.downloads') }}" class="nav-link {{ request()->routeIs('user.downloads') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        Downloads
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-title">Account</div>
                    <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    <a href="{{ route('user.settings') }}" class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="user-main">
            <header class="user-header-bar">
                <div class="header-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>@yield('page-title', 'E-Library')</h1>
                </div>
                
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">User</div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="user-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('userSidebar').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('userSidebar');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
