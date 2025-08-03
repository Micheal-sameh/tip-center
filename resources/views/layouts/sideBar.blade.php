<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
    <style>
        :root {
            --primary-blue: #1976D2;
            --sidebar-blue: #1565C0;
            --hover-blue: #1E88E5;
            --text-light: #E3F2FD;
            --sidebar-width: 220px;
            --mobile-header-height: 60px;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        /* Layout Structure */
        .app-container {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--mobile-header-height);
            background-color: var(--sidebar-blue);
            color: white;
            z-index: 1050;
            padding: 0 15px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        [dir="rtl"] .mobile-header {
            left: auto;
            right: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-blue);
            color: var(--text-light);
            z-index: 1040;
            overflow-y: auto;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        [dir="rtl"] .sidebar {
            left: auto;
            right: 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }

        /* Content Area */
        .content-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #fff;
            transition: margin 0.3s ease;
        }

        [dir="rtl"] .content-wrapper {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }

        .main-content {
            padding: 20px;
            min-height: calc(100vh - var(--mobile-header-height));
        }

        /* Navigation Items */
        .nav-item {
            padding: 12px 20px;
            transition: background-color 0.2s;
        }

        .nav-item:hover {
            background-color: var(--hover-blue);
        }

        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            display: block;
        }

        /* Profile Image */
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin: 20px auto;
            display: block;
        }

        /* Language Switcher */
        .language-switcher {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1050;
        }

        [dir="rtl"] .language-switcher {
            left: auto;
            right: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            [dir="rtl"] .content-wrapper {
                margin-right: 0;
            }

            .mobile-header {
                display: flex;
            }

            .main-content {
                padding-top: calc(var(--mobile-header-height) + 20px);
            }
        }

        /* Prevent horizontal scrolling */
        .main-content > .container {
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Button styles */
        .btn-menu {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.25rem;
            padding: 0;
            cursor: pointer;
        }

        /* Ensure no overflow */
        body {
            overflow-y: auto;
        }
    </style>
</head>

<body class="app-container">
    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="btn-menu" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="d-flex align-items-center">

            <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}"
                 alt="Profile"
                 class="rounded-circle"
                 style="width: 40px; height: 40px; object-fit: cover;">
        </div>
        <button class="btn-menu" id="backButton">
            <i class="fas fa-arrow-left"></i>
        </button>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="text-center py-3">
                        <a href="{{ route('users.profile') }}">

            <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}"
                 alt="Profile"
                 class="profile-img">
                 </a>
        </div>

        <nav>
            <ul class="list-unstyled">
                @auth
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">
                            <i class="fas fa-home me-2"></i> {{ __('trans.home') }}
                        </a>
                    </li>

                    @can('users_view')
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="fas fa-users me-2"></i> {{ __('trans.users') }}
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('attendances.index') }}" class="nav-link">
                            <i class="fas fa-calendar-check me-2"></i> {{ __('trans.attendence') }}
                        </a>
                    </li>

                    @can('professors_view')
                    <li class="nav-item">
                        <a href="{{ route('professors.index') }}" class="nav-link">
                            <i class="fas fa-chalkboard-teacher me-2"></i> {{ __('trans.professors') }}
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('sessions.index') }}" class="nav-link">
                            <i class="fas fa-clock me-2"></i> {{ __('trans.sessions') }}
                        </a>
                    </li>

                    @can('settings_update')
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link">
                            <i class="fas fa-cog me-2"></i> {{ __('trans.settings') }}
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link">
                            <i class="fas fa-file-alt me-2"></i> {{ __('trans.session_reports') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.student') }}" class="nav-link">
                            <i class="fas fa-user-graduate me-2"></i> {{ __('trans.student_reports') }}
                        </a>
                    </li>

                    @can('students_view')
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}" class="nav-link">
                            <i class="fas fa-user-friends me-2"></i> {{ __('trans.students') }}
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link bg-transparent border-0 w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> {{ __('trans.logout') }}
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('loginPage') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('trans.login') }}
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>

        <button class="btn btn-primary rounded-circle language-switcher" id="languageSwitcher">
            <i class="fas fa-globe"></i>
        </button>
    </aside>

    <!-- Main Content -->
    <main class="content-wrapper">
        <div class="main-content">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const backBtn = document.getElementById('backButton');
            const languageBtn = document.getElementById('languageSwitcher');

            // Toggle sidebar on mobile
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Back button functionality
            backBtn.addEventListener('click', function() {
                window.history.back();
            });

            // Language switcher
            languageBtn.addEventListener('click', function() {
                const currentLang = "{{ app()->getLocale() }}";
                const newLang = currentLang === 'en' ? 'ar' : 'en';
                window.location.href = `/lang/${newLang}`;
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = toggleBtn.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth <= 991) {
                    sidebar.classList.remove('show');
                }
            });

            // Adjust content padding on resize
            function adjustLayout() {
                const mobileHeader = document.querySelector('.mobile-header');
                const mainContent = document.querySelector('.main-content');

                if (window.innerWidth <= 991) {
                    mainContent.style.paddingTop = `${mobileHeader.offsetHeight + 20}px`;
                } else {
                    mainContent.style.paddingTop = '20px';
                }
            }

            // Initial adjustment
            adjustLayout();

            // Adjust on resize
            window.addEventListener('resize', adjustLayout);
        });
    </script>

    @stack('scripts')
</body>
</html>