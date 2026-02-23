<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - {{ config('app.name') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Favicon --}}
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif
    
    <script>
        const storedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
    </script>

    <style>
        :root {
            --sidebar-width: 280px;
            --accent-color: #6366f1;
        }

        [data-bs-theme="dark"] {
            --primary-bg: #0b0f19;
            --sidebar-bg: #111827;
            --card-bg: #1f2937;
            --text-color: #f3f4f6;
            --text-muted: #9ca3af;
            --text-emphasis: #ffffff;
            --border-color: rgba(255, 255, 255, 0.05);
            --nav-link-hover: rgba(99, 102, 241, 0.1);
            --input-bg: rgba(255, 255, 255, 0.03);
            --input-border: rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="light"] {
            --primary-bg: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --text-emphasis: #0f172a;
            --border-color: rgba(0, 0, 0, 0.08);
            --nav-link-hover: rgba(99, 102, 241, 0.05);
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-color);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
        }

        .brand-logo-container img {
            max-height: 40px;
            width: auto;
            object-fit: contain;
        }

        [data-bs-theme="dark"] .logo-dark-admin { display: block !important; }
        [data-bs-theme="light"] .logo-light-admin { display: block !important; }
        
        /* Fallback if one version is missing */
        [data-bs-theme="dark"] .logo-dark-admin:not([src]), 
        [data-bs-theme="light"] .logo-light-admin:not([src]) {
            display: none !important;
        }
        
        [data-bs-theme="dark"] .logo-dark-admin:not([src]) ~ .brand-text-admin,
        [data-bs-theme="light"] .logo-light-admin:not([src]) ~ .brand-text-admin {
            display: inline-block !important;
        }
        
        /* If no logos at all, show text */
        .brand-logo-container:not(:has(img)) .brand-text-admin {
            display: inline-block !important;
        }

        .nav-list {
            padding: 0 1rem;
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .nav-link i {
            width: 1.5rem;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color);
            background-color: var(--nav-link-hover);
        }

        .nav-link.active {
            background-color: var(--accent-color);
            color: white !important;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .admin-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            color: var(--text-color);
        }

        .admin-card h1, .admin-card h2, .admin-card h3, .admin-card h4, .admin-card h5, .admin-card h6 {
            color: var(--text-emphasis);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
        }

        .table-custom {
            color: var(--text-color);
        }

        .table-custom thead th {
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom tbody td {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0.75rem;
        }

        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-color);
            border-radius: 10px;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.1);
        }

        .theme-toggle-admin, .lang-toggle-admin {
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            margin-right: 0.8rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .theme-toggle-admin:hover, .lang-toggle-admin:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        /* Notification Styles */
        .admin-notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 10px;
            border: 2px solid var(--card-bg);
        }
        .notification-item-admin {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: var(--text-color);
        }
        .notification-item-admin:hover {
            background: var(--nav-link-hover);
        }
        .notification-item-admin .message-text {
            color: var(--text-color);
        }
        .bg-unread-admin {
            background: rgba(99, 102, 241, 0.05);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center">
                @php
                    $logoLight = setting('site_logo_light');
                    $logoDark = setting('site_logo_dark');
                @endphp
                
                @if($logoLight || $logoDark)
                    <div class="brand-logo-container">
                        @if($logoLight)
                            <img src="{{ Storage::url($logoLight) }}" alt="{{ setting('site_name') }}" class="logo-light-admin d-none">
                        @endif
                        @if($logoDark)
                            <img src="{{ Storage::url($logoDark) }}" alt="{{ setting('site_name') }}" class="logo-dark-admin d-none">
                        @endif
                        <span class="brand-text-admin ms-2 d-none">{{ setting('site_name', 'FutureBooks') }}</span>
                    </div>
                @else
                    <i class="fas fa-rocket me-2"></i>{{ setting('site_name', 'FutureBooks') }}
                @endif
            </a>
        </div>
        
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> @lang('messages.dashboard')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> @lang('messages.categories')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> @lang('messages.books')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-box-open"></i> @lang('messages.orders')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> @lang('messages.users')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i> @lang('messages.settings')
                </a>
            </li>
            <li class="mt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-power-off"></i> @lang('messages.logout')
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold">@yield('page_title', __('messages.admin_panel'))</h2>
                <div class="d-flex align-items-center">
                    <!-- Language Toggle -->
                    <div class="dropdown me-2">
                        <a class="lang-toggle-admin dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'bn') }}">বাংলা</a></li>
                        </ul>
                    </div>

                    <!-- Notification Toggle -->
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                    @endphp
                    <div class="dropdown me-2">
                        <a class="lang-toggle-admin position-relative" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="admin-notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 overflow-hidden" style="width: 320px;">
                            <div class="p-3 bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                @if($unreadCount > 0)
                                    <form action="{{ route('user.notifications.markAllRead') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm text-white-50 p-0 border-0 small">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                            <div style="max-height: 350px; overflow-y: auto;">
                                @forelse($recentNotifications as $notification)
                                    <div class="notification-item-admin {{ !$notification->is_read ? 'bg-unread-admin' : '' }}" onclick="markAsReadAdmin(this, {{ $notification->id }})">
                                        <div class="d-flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-accent bg-opacity-10 text-accent d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                                    <i class="fas {{ $notification->type === 'new_order' ? 'fa-cart-plus' : 'fa-bell' }} small"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-1 small message-text">{{ $notification->message }}</p>
                                                <span class="text-muted smaller" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted">No new notifications</div>
                                @endforelse
                            </div>
                            @if($recentNotifications->count() > 0)
                                <div class="p-2 text-center border-top" style="border-color: var(--border-color) !important;">
                                    <a href="{{ route('admin.orders.index') }}" class="text-accent small fw-bold text-decoration-none">Review Orders</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="theme-toggle-admin" onclick="toggleTheme()" title="Toggle Theme">
                        <i class="fas fa-sun d-none" id="sun-icon"></i>
                        <i class="fas fa-moon" id="moon-icon"></i>
                    </div>
                    <span class="me-3 text-muted d-none d-md-block">{{ auth()->user()->name }}</span>
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; font-size: 1.1rem; background: linear-gradient(135deg, var(--accent-color), #4338ca);">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success bg-opacity-10 border-success text-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateIcons(theme) {
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            if (theme === 'light') {
                sunIcon?.classList.add('d-none');
                moonIcon?.classList.remove('d-none');
            } else {
                sunIcon?.classList.remove('d-none');
                moonIcon?.classList.add('d-none');
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcons(newTheme);
        }

        function markAsReadAdmin(element, id) {
            fetch(`/user/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
            .then(data => {
                if(data.success) {
                    element.classList.remove('bg-unread-admin');
                    const badge = document.querySelector('.admin-notification-badge');
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
            updateIcons(document.documentElement.getAttribute('data-bs-theme'));
        });
    </script>
    @yield('scripts')
</body>
</html>
