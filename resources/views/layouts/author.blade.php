<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Author - E-Library')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .author-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .author-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .author-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand__mark {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.2s ease;
        }

        .brand__mark:hover {
            transform: scale(1.05);
        }

        .brand__text {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.5);
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            margin-bottom: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            transform: translateX(4px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-footer {
            padding: 2rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            bottom: 0;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            backdrop-filter: blur(10px);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.2s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .user-info {
            flex: 1;
        }

        .user-actions {
            display: flex;
            align-items: center;
        }

        .logout-form {
            display: contents;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.8);
            border-color: rgba(239, 68, 68, 0.8);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        .user-name {
            font-weight: 600;
            color: white;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .author-badge {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Main Content */
        .author-main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .author-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-header {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--dark-color);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-header:hover {
            background: var(--light-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .author-content {
            flex: 1;
            padding: 2rem;
            background: var(--light-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .author-sidebar {
                transform: translateX(-100%);
            }

            .author-sidebar.show {
                transform: translateX(0);
            }

            .author-main {
                margin-left: 0;
            }

            .author-content {
                padding: 1rem;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-menu-toggle:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="author-layout">
        <!-- Sidebar -->
        <aside class="author-sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="author-logo">
                    <div class="brand__mark">E</div>
                    <div class="brand__text">E-Library</div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-title">Dashboard</div>
                    <a href="{{ route('author.dashboard') }}" class="nav-link {{ request()->routeIs('author.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Dashboard
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-title">My Books</div>
                    <a href="{{ route('author.books.index') }}" class="nav-link {{ request()->routeIs('author.books.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        All Books
                    </a>
                    <!-- <a href="{{ route('author.books.create') }}" class="nav-link {{ request()->routeIs('author.books.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        Add New Book
                    </a> -->
                    <a href="{{ route('author.books.published') }}" class="nav-link {{ request()->routeIs('author.books.published') ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i>
                        Published Books
                    </a>
                    <a href="{{ route('author.books.drafts') }}" class="nav-link {{ request()->routeIs('author.books.drafts') ? 'active' : '' }}">
                        <i class="fas fa-edit"></i>
                        Draft Books
                    </a>
                    <a href="{{ route('author.analytics') }}" class="nav-link {{ request()->routeIs('author.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Book Analytics
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Content Management</div>
                    <a href="{{ route('author.categories') }}" class="nav-link {{ request()->routeIs('author.categories') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        Categories
                    </a>
                    <a href="{{ route('author.tags') }}" class="nav-link {{ request()->routeIs('author.tags') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Tags
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Reader Engagement</div>
                    <a href="{{ route('author.reviews') }}" class="nav-link {{ request()->routeIs('author.reviews') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        Reviews & Comments
                    </a>
                    <a href="{{ route('author.reading-progress') }}" class="nav-link {{ request()->routeIs('author.reading-progress') ? 'active' : '' }}">
                        <i class="fas fa-book-reader"></i>
                        Reading Progress
                    </a>
                    <a href="{{ route('author.favorites') }}" class="nav-link {{ request()->routeIs('author.favorites') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i>
                        Favorites
                    </a>
                    <a href="{{ route('author.downloads') }}" class="nav-link {{ request()->routeIs('author.downloads') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        Downloads
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Library</div>
                    <a href="{{ route('author.library') }}" class="nav-link {{ request()->routeIs('author.library') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        Browse Library
                    </a>
                    <a href="{{ route('author.reading-history') }}" class="nav-link {{ request()->routeIs('author.reading-history') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        Reading History
                    </a>
                    <a href="{{ route('author.my-favorites') }}" class="nav-link {{ request()->routeIs('author.my-favorites') ? 'active' : '' }}">
                        <i class="fas fa-bookmark"></i>
                        My Favorites
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Account</div>
                    <a href="{{ route('author.profile') }}" class="nav-link {{ request()->routeIs('author.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        My Profile
                    </a>
                    <!-- <a href="{{ route('author.settings') }}" class="nav-link {{ request()->routeIs('author.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a> -->
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">
                            <span class="author-badge">Author</span>
                        </div>
                    </div>
                    <div class="user-actions">
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="author-main">
            <header class="author-header">
                <div class="header-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn-header">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </button>
                        <button class="btn-header">
                            <i class="fas fa-search"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </header>

            <div class="author-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Active link highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
