<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Settings --}}
    <title>@yield('title', setting('default_meta_title')) - {{ setting('site_name', config('app.name')) }}</title>
    <meta name="description" content="@yield('meta_description', setting('default_meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('default_meta_keywords'))">
    
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', setting('default_meta_title'))">
    <meta property="og:description" content="@yield('meta_description', setting('default_meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', setting('og_image') ? Storage::url(setting('og_image')) : '')">

    @stack('head')

    @if(setting('google_analytics_id'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ setting('google_analytics_id') }}');
        </script>
    @endif

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script>
        const storedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
    </script>

    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #a855f7;
        }

        [data-bs-theme="dark"] {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #e2e8f0;
            --nav-bg: rgba(15, 23, 42, 0.9);
            --border-color: rgba(255, 255, 255, 0.1);
            --input-bg: #334155;
            --input-border: #475569;
        }

        [data-bs-theme="light"] {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --nav-bg: rgba(255, 255, 255, 0.9);
            --border-color: rgba(0, 0, 0, 0.1);
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background-color: var(--nav-bg) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            font-weight: 700;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }

        .theme-toggle {
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: var(--primary-color);
            color: white;
        }

        .lang-switch {
            padding: 5px 12px;
            border-radius: 20px;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .lang-switch:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Improved Navbar Styling */
        .navbar {
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .nav-link {
            position: relative;
            font-weight: 500;
            padding: 8px 16px !important;
            margin: 0 4px;
            border-radius: 12px;
            transition: all 0.3s ease;
            color: var(--text-color) !important;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color) !important;
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 5px;
            left: 16px;
            right: 16px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: calc(100% - 32px);
        }

        @media (min-width: 992px) {
            .navbar-nav.mx-auto {
                display: flex;
                align-items: center;
                gap: 10px;
            }
        }

        .dropdown-menu {
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }

        /* Notification Styles */
        .notification-item:hover {
            background-color: rgba(99, 102, 241, 0.05) !important;
        }
        .bg-light-blue {
            background-color: rgba(99, 102, 241, 0.08) !important;
        }
        .hover-white:hover {
            color: white !important;
            text-decoration: underline !important;
        }
        [data-bs-theme="dark"] .notification-item p {
            color: #f1f5f9 !important;
        }
        [data-bs-theme="light"] .notification-item p {
            color: #1e293b !important;
        }

        /* Footer Styling */
        footer {
            background-color: var(--card-bg);
            border-top: 1px solid var(--border-color);
            padding: 80px 0 30px;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .footer-heading {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--text-color);
            position: relative;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 30px;
            height: 2px;
            background: var(--primary-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #64748b; /* Slate-500 for light mode balance */
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        [data-bs-theme="dark"] .footer-links a {
            color: #94a3b8; /* Original slate-400 for dark mode */
        }

        .footer-links a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .social-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .footer-bottom {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            color: #94a3b8;
            font-size: 0.9rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="{{ route('home') }}">
                @php
                    $logoLight = setting('site_logo_light');
                    $logoDark = setting('site_logo_dark');
                @endphp
                
                @if($logoLight || $logoDark)
                    @if($logoLight)
                        <img src="{{ Storage::url($logoLight) }}" alt="{{ setting('site_name') }}" height="35" class="d-inline-block align-top logo-light">
                    @endif
                    @if($logoDark)
                        <img src="{{ Storage::url($logoDark) }}" alt="{{ setting('site_name') }}" height="35" class="d-inline-block align-top logo-dark d-none">
                    @endif
                @else
                    <i class="fas fa-rocket me-2"></i>{{ setting('site_name', 'FutureBooks') }}
                @endif
            </a>
            
            <div class="d-flex align-items-center d-lg-none ms-auto">
                <div class="theme-toggle me-2" onclick="toggleTheme()">
                    <i class="fas fa-sun d-none" id="sun-icon-mobile"></i>
                    <i class="fas fa-moon" id="moon-icon-mobile"></i>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            @lang('messages.home')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active fw-bold' : '' }}" href="{{ route('about') }}">
                            @lang('messages.about_us')
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active fw-bold' : '' }}" href="{{ route('categories.index') }}">
                            @lang('messages.category')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active fw-bold' : '' }}" href="{{ route('contact') }}">
                            @lang('messages.contact_us')
                        </a>
                    </li>
                   
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Language Switcher -->
                    <li class="nav-item me-3">
                        <div class="dropdown">
                            <a class="lang-switch dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                                <li><a class="dropdown-item" href="{{ route('lang.switch', 'bn') }}">বাংলা</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item d-none d-lg-block me-3">
                        <div class="theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
                            <i class="fas fa-sun d-none" id="sun-icon"></i>
                            <i class="fas fa-moon" id="moon-icon"></i>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item me-2">
                            <a class="nav-link" href="{{ route('login') }}">@lang('messages.login')</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 rounded-pill" href="{{ route('register') }}">@lang('messages.register')</a>
                        </li>
                    @else
                        {{-- Notification Bell --}}
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                            $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                        @endphp
                        <li class="nav-item dropdown me-3 position-relative">
                            <a class="nav-link p-0 position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 overflow-hidden" style="width: 320px; border-radius: 15px;">
                                <div class="bg-primary p-3 d-flex justify-content-between align-items-center">
                                    <h6 class="text-white mb-0 fw-bold">Notifications</h6>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('user.notifications.markAllRead') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm text-white-50 p-0 border-0 small hover-white">Mark all as read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                                    @forelse($recentNotifications as $notification)
                                        <div class="p-3 border-bottom notification-item {{ !$notification->is_read ? 'bg-light-blue' : '' }}" 
                                             onclick="markAsRead(this, {{ $notification->id }})" 
                                             style="cursor: pointer; transition: background 0.2s;">
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($notification->type === 'order_approved')
                                                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-times"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 small text-dark fw-bold">{{ $notification->message }}</p>
                                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-5 text-center text-muted">
                                            <i class="fas fa-bell-slash fa-2x mb-3 opacity-25"></i>
                                            <p class="mb-0">No notifications yet</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($recentNotifications->count() > 0)
                                    <div class="p-2 border-top text-center bg-light">
                                        <a href="{{ route('user.notifications.index') }}" class="text-primary small fw-bold text-decoration-none">View All Notifications</a>
                                    </div>
                                @endif
                            </div>
                        </li>

                        {{-- Cart Icon --}}
                        <li class="nav-item me-3 position-relative">
                            <a class="nav-link p-0 position-relative" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar">
                                <i class="fas fa-shopping-cart fs-5"></i>
                                <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary {{ count(Session::get('cart', [])) > 0 ? '' : 'd-none' }}" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                                    {{ count(Session::get('cart', [])) }}
                                </span>
                            </a>
                        </li>

                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">@lang('messages.dashboard')</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('user.my-books') }}">@lang('messages.my_books')</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">@lang('messages.dashboard')</a></li> -->
                        @endif
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @php
                                    $navAvatarUrl = auth()->user()->avatar ? (str_contains(auth()->user()->avatar, 'http') ? auth()->user()->avatar : Storage::url(auth()->user()->avatar)) : null;
                                @endphp
                                @if($navAvatarUrl)
                                    <img src="{{ $navAvatarUrl }}" class="rounded-circle me-2" width="35" height="35" style="object-fit: cover; border: 2px solid var(--primary-color);">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2 text-white fw-bold" style="width: 35px; height: 35px;">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" style="min-width: 200px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user-circle me-3 text-primary opacity-75" style="width: 20px;"></i>
                                        @lang('messages.profile')
                                    </a>
                                </li>
                                @if(auth()->user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-chart-line me-3 text-primary opacity-75" style="width: 20px;"></i>
                                            @lang('messages.dashboard')
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('user.dashboard') }}">
                                            <i class="fas fa-th-large me-3 text-primary opacity-75" style="width: 20px;"></i>
                                            @lang('messages.dashboard')
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider opacity-10"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item d-flex align-items-center py-2 text-danger" type="submit">
                                            <i class="fas fa-sign-out-alt me-3 opacity-75" style="width: 20px;"></i>
                                            @lang('messages.logout')
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main style="margin-top: 80px; min-height: 80vh;">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>
    
    <footer class="mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('home') }}" class="footer-logo mb-4 d-inline-block">
                        @if($logoLight || $logoDark)
                            @if($logoLight)
                                <img src="{{ Storage::url($logoLight) }}" alt="{{ setting('site_name') }}" height="40" class="logo-light">
                            @endif
                            @if($logoDark)
                                <img src="{{ Storage::url($logoDark) }}" alt="{{ setting('site_name') }}" height="40" class="logo-dark d-none">
                            @endif
                        @else
                            <i class="fas fa-rocket me-2"></i>{{ setting('site_name', 'FutureBooks') }}
                        @endif
                    </a>
                    <p class="text-muted pe-lg-5">
                        @lang('messages.about_text')
                    </p>
                    <div class="social-links mt-4">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">@lang('messages.quick_links')</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">@lang('messages.home')</a></li>
                        <li><a href="{{ route('about') }}">@lang('messages.about_us')</a></li>
                        <li><a href="{{ route('categories.index') }}">@lang('messages.category')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('messages.contact_us')</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">@lang('messages.privacy_policy')</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('privacy') }}">@lang('messages.privacy_policy')</a></li>
                        <li><a href="{{ route('terms') }}">@lang('messages.terms_of_service')</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">@lang('messages.contact_us')</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-envelope me-2"></i> support@futurebooks.com</a></li>
                        <li><a href="#"><i class="fas fa-phone me-2"></i> +880 123 456 789</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt me-2"></i> Dhaka, Bangladesh</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ setting('footer_copyright', 'FutureBooks. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Cart Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel" style="width: 400px; background-color: var(--card-bg);">
        <div class="offcanvas-header border-bottom p-4">
            <h5 class="offcanvas-title fw-bold" id="cartSidebarLabel">
                <i class="fas fa-shopping-cart me-2 text-primary"></i> Your Cart
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div id="cart-items-container" class="p-4">
                <!-- Cart items will be loaded here via JS -->
                @php $cartItems = Session::get('cart', []); @endphp
                @forelse($cartItems as $key => $item)
                    <div class="cart-item mb-4 d-flex gap-3 position-relative p-2 rounded-3 hover-bg">
                        <img src="{{ Storage::url($item['cover_image']) }}" class="rounded-2" width="60" height="80" style="object-fit: cover;">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $item['title'] }}</h6>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="text-primary fw-bold mb-0">৳{{ number_format($item['price'] ?? 0, 0) }}</p>
                                <span class="badge {{ ($item['format'] ?? 'pdf') === 'hardcopy' ? 'bg-primary text-primary' : 'bg-danger text-danger' }} bg-opacity-10" style="font-size: 0.65rem;">
                                    {{ ($item['format'] ?? 'pdf') === 'hardcopy' ? 'Physical' : 'PDF' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="small text-muted mb-0">Qty: {{ $item['quantity'] ?? 1 }}</p>
                                <button onclick="removeFromCart('{{ $key }}')" class="btn btn-link text-danger p-0 text-decoration-none small">
                                    <i class="fas fa-trash-alt me-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                        <p>Your cart is empty</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Explore Books</a>
                    </div>
                @endforelse
            </div>
        </div>
            <div id="cart-footer-container">
                @if(count($cartItems) > 0)
                <div class="offcanvas-footer p-4 border-top">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total:</span>
                        <h4 class="fw-bold text-primary mb-0">৳{{ number_format(collect($cartItems)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 1)), 0) }}</h4>
                    </div>
                    <a href="{{ route('user.cart.checkout') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                        <i class="fas fa-shopping-bag me-2"></i>Proceed to Checkout
                    </a>
                    <p class="small text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Digital products available instantly.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateIcons(theme) {
            const sunIcons = [document.getElementById('sun-icon'), document.getElementById('sun-icon-mobile')];
            const moonIcons = [document.getElementById('moon-icon'), document.getElementById('moon-icon-mobile')];
            
            const logoLights = document.querySelectorAll('.logo-light');
            const logoDarks = document.querySelectorAll('.logo-dark');

            if (theme === 'light') {
                sunIcons.forEach(icon => icon?.classList.add('d-none'));
                moonIcons.forEach(icon => icon?.classList.remove('d-none'));
                logoLights.forEach(img => img.classList.remove('d-none'));
                logoDarks.forEach(img => img.classList.add('d-none'));
            } else {
                sunIcons.forEach(icon => icon?.classList.remove('d-none'));
                moonIcons.forEach(icon => icon?.classList.add('d-none'));
                logoLights.forEach(img => img.classList.add('d-none'));
                logoDarks.forEach(img => img.classList.remove('d-none'));
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcons(newTheme);
        }

        function refreshCartUI() {
            fetch('{{ route('user.cart.items') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('cart-items-container');
                const badge = document.getElementById('cart-badge');
                const footerContainer = document.getElementById('cart-footer-container');
                
                // Update badge with total quantity
                const totalQty = data.items.reduce((sum, item) => sum + (item.quantity || 1), 0);
                if(totalQty > 0) {
                    badge.innerText = totalQty;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }

                // Update items
                if(data.items.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5 opacity-50">
                            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                            <p>Your cart is empty</p>
                            <a href="{{ route('categories.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Explore Books</a>
                        </div>`;
                    footerContainer.innerHTML = '';
                } else {
                    let html = '';
                    data.items.forEach(item => {
                        const formatLabel = item.format === 'hardcopy' ? 'Physical' : 'PDF';
                        const formatClass = item.format === 'hardcopy' ? 'bg-primary text-primary' : 'bg-danger text-danger';
                        
                        html += `
                            <div class="cart-item mb-4 d-flex gap-3 position-relative p-2 rounded-3 hover-bg">
                                <img src="/storage/${item.cover_image}" class="rounded-2" width="60" height="80" style="object-fit: cover;">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold mb-1 text-truncate">${item.title}</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <p class="text-primary fw-bold mb-0">৳${new Intl.NumberFormat().format(item.price)}</p>
                                        <span class="badge ${formatClass} bg-opacity-10" style="font-size: 0.65rem;">${formatLabel}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="small text-muted mb-0">Qty: ${item.quantity}</p>
                                        <button onclick="removeFromCart('${item.cartKey}')" class="btn btn-link text-danger p-0 text-decoration-none small">
                                            <i class="fas fa-trash-alt me-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                    });
                    container.innerHTML = html;
                    
                    // Update footer dynamically
                    footerContainer.innerHTML = `
                        <div class="offcanvas-footer p-4 border-top">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total:</span>
                                <h4 class="fw-bold text-primary mb-0">৳${new Intl.NumberFormat().format(data.total)}</h4>
                            </div>
                            <a href="{{ route('user.cart.checkout') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                                <i class="fas fa-shopping-bag me-2"></i>Proceed to Checkout
                            </a>
                            <p class="small text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Digital products available instantly.</p>
                        </div>`;
                }
            });
        }

        let cartOffcanvas = null;
        function showCart() {
            if (!cartOffcanvas) {
                cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartSidebar'));
            }
            cartOffcanvas.show();
        }

        function addToCart(bookId, format = null, quantity = 1) {
            let payload = { quantity: quantity };
            if (format) payload.format = format;

            fetch(`/user/cart/add/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    refreshCartUI();
                    showCart();
                } else {
                    alert(data.message || 'Error adding to cart');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function removeFromCart(cartKey) {
            fetch(`/user/cart/remove/${cartKey}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    refreshCartUI();
                }
            });
        }

        function markAsRead(element, id) {
            fetch(`/user/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
            .then(data => {
                if(data.success) {
                    element.classList.remove('bg-light-blue');
                    const badge = document.querySelector('.nav-item .badge:not(#cart-badge)');
                    if(badge) {
                        let count = parseInt(badge.innerText);
                        if(count > 1) {
                            badge.innerText = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            updateIcons(savedTheme);
        });
    </script>
    @yield('scripts')
</body>
</html>
