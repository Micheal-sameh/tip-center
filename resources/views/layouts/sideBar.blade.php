<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    @php
        $logo = App\Models\Setting::where('name', 'logo')->first();
        $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
    @endphp

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', config('app.name'))</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            :root {
                --primary-blue: #1976D2;
                --dark-blue: #0D47A1;
                --light-blue: #BBDEFB;
                --accent-blue: #2196F3;
                --sidebar-blue: #1565C0;
                --hover-blue: #1E88E5;
                --text-light: #E3F2FD;
            }

            html, body {
                overflow-x: hidden;
                margin: 0;
                padding: 0;
                width: 100%;
                background-color: #E3F2FD;
            }

            /* Sidebar Styles */
            #sidebar {
                position: fixed;
                top: 0;
                height: 100vh;
                width: 200px;
                background-color: var(--sidebar-blue);
                color: var(--text-light);
                transition: left 0.3s ease, right 0.3s ease;
                z-index: 1000;
                left: 0;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            [dir="rtl"] #sidebar {
                left: auto;
                right: 0px;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            }

            #sidebar.show {
                left: 0;
            }

            [dir="rtl"] #sidebar.show {
                right: 0;
            }

            /* Content Area */
            .content-wrapper {
                display: flex;
                margin: 0;
                padding: 0;
            }

            .content-area {
                width: calc(100vw - 200px);
                flex-grow: 1;
                margin-left: 200px;
                background-color: white;
                min-height: 100vh;
            }

            [dir="rtl"] .content-area {
                margin-right: 200px;
            }

            /* Sidebar Toggle Button */
            .btn-toggle-sidebar {
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: var(--primary-blue);
                border: none;
                padding: 8px 12px;
                cursor: pointer;
                color: white;
                border-radius: 4px;
            }

            [dir="rtl"] .btn-toggle-sidebar {
                right: 20px;
                left: auto;
            }

            /* Mobile View */
            @media (max-width: 767px) {
                #sidebar {
                    left: -200px;
                }

                [dir="rtl"] #sidebar {
                    right: -200px;
                }

                #sidebar.show {
                    left: 0;
                }

                [dir="rtl"] #sidebar.show {
                    right: 0;
                }

                .content-area {
                    margin-left: 0;
                    width: 100vw;
                }

                [dir="rtl"] .content-area {
                    margin-right: 0;
                }
            }

            /* Sidebar Menu Items */
            #sidebar ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            #sidebar .nav-item {
                padding: 10px 15px;
                transition: background-color 0.2s;
            }

            #sidebar .nav-item:hover {
                background-color: var(--hover-blue);
            }

            #sidebar .nav-item a {
                color: var(--text-light);
                text-decoration: none;
                display: block;
            }

            #sidebar .nav-item button {
                color: var(--text-light);
                text-decoration: none;
                display: block;
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                padding: 0;
            }

            /* World Icon Button */
            #sidebar .world-icon-btn {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: var(--accent-blue);
                border: none;
                padding: 10px;
                cursor: pointer;
                color: white;
                font-size: 20px;
                z-index: 1001;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #sidebar .world-icon-btn:hover {
                background-color: var(--primary-blue);
            }

            [dir="rtl"] #sidebar .world-icon-btn {
                left: auto;
                right: 50%;
                transform: translateX(50%);
            }

            #sidebar img {
                width: 150px;
                height: auto;
                margin: 20px auto;
                display: block;
                padding: 0 20px;
            }

            /* Back Arrow Button */
            .btn-back {
                position: fixed;
                top: 20px;
                left: 60px;
                z-index: 1002;
                background: var(--primary-blue);
                border: none;
                padding: 8px 12px;
                cursor: pointer;
                color: white;
                border-radius: 4px;
            }

            [dir="rtl"] .btn-back {
                right: 60px;
                left: auto;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row content-wrapper">
                <!-- Sidebar -->
                <div id="sidebar">
                    <img src="{{ $faviconUrl }}" alt="Logo" class="img-fluid">
                    <ul class="nav flex-column">
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/') }}">{{ __('trans.home') }}</a>
                            </li>

                            @can('users_view')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}">{{ __('trans.users') }}</a>
                                </li>
                            @endcan

                            @can('professors_view')
                                <li class="nav-item">
                                    <a href="{{ route('professors.index') }}">{{ __('trans.professors') }}</a>
                                </li>
                            @endcan

                            @can('students_view')
                                <li class="nav-item">
                                    <a href="{{ route('students.index') }}">{{ __('trans.students') }}</a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('sessions.index') }}">{{ __('trans.sessions') }}</a>
                            </li>

                            @can('settings_update')
                                <li class="nav-item">
                                    <a href="{{ route('settings.index') }}">{{ __('trans.settings') }}</a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('attendances.index') }}">{{ __('trans.attendence') }}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('reports.index') }}">{{ __('trans.session_reports') }}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('reports.student') }}">{{ __('trans.student_reports') }}</a>
                            </li>
                        @endauth

                        @auth
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">{{ __('trans.logout') }}</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('loginPage') }}">{{ __('trans.login') }}</a>
                            </li>
                        @endauth
                    </ul>

                    <button class="world-icon-btn" id="languageSwitcher" aria-label="Change Language">
                        <i class="fas fa-globe"></i>
                    </button>
                </div>

                <!-- Content Area -->
                <div class="content-area p-4">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Mobile Toggle Button -->
        <button class="btn-toggle-sidebar d-md-none" id="toggleSidebar" aria-label="Open Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Back Arrow Button for Mobile -->
        <button class="btn-back d-md-none" id="backArrow" aria-label="Go Back">
            <i class="fas fa-arrow-left"></i>
        </button>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.getElementById("toggleSidebar").addEventListener("click", function() {
                const sidebar = document.getElementById("sidebar");
                sidebar.classList.toggle("show");
                this.setAttribute('aria-label', sidebar.classList.contains("show") ? 'Close Sidebar' : 'Open Sidebar');
            });

            document.getElementById('languageSwitcher').addEventListener('click', function() {
                const currentLang = "{{ app()->getLocale() }}";
                const newLang = currentLang === 'en' ? 'ar' : 'en';
                window.location.href = `/lang/${newLang}`;
            });

            document.getElementById("backArrow").addEventListener("click", function() {
                window.history.back();
            });
        </script>

        @stack('scripts')
    </body>
</html>