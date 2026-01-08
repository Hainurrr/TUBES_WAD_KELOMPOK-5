<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Admin Dashboard - TelyuCan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .nav-pills .nav-link.active {
            background-color: #800000;
        }
        .btn-maroon {
            background-color: #800000;
            color: white;
        }
        .btn-maroon:hover {
            background-color: #660000;
            color: white;
        }
        .text-maroon {
            color: #800000;
        }

        /* Sidebar Transition */
        #sidebar-wrapper {
            transition: width 0.3s;
            overflow: hidden;
            width: 280px;
        }
        #sidebar-wrapper.collapsed {
            width: 80px;
        }
        #sidebar-wrapper.collapsed .sidebar-text {
            display: none;
        }
        #sidebar-wrapper.collapsed .sidebar-header-text {
            display: none;
        }
        #sidebar-wrapper.collapsed .sidebar-logo {
            margin-right: 0 !important;
        }
        /* Center arrow when collapsed */
        #sidebar-wrapper.collapsed #sidebarToggleInternal {
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="flex-shrink-0 bg-dark sticky-top vh-100">
            @include('layouts.admin-sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
            <!-- Topbar -->
            <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-4 py-3 sticky-top">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold text-secondary fs-6">Admin Panel</span>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <!-- Profile Dropdown (Moved from Sidebar) -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->avatar && Auth::user()->avatar !== 'default_avatar.png' ? '/uploads/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=800000&color=fff' }}" alt="" width="32" height="32" class="rounded-circle me-2 object-fit-cover">
                                <strong class="d-none d-md-inline">{{ Auth::user()->name ?? 'Administrator' }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile & Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Start / Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            const toggleBtn = document.getElementById('sidebarToggleInternal');
            const toggleIcon = document.getElementById('sidebarToggleIcon');
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                
                // Logika Toggle Icon
                if (sidebar.classList.contains('collapsed')) {
                    toggleIcon.classList.remove('bi-arrow-left-circle');
                    toggleIcon.classList.add('bi-arrow-right-circle');
                } else {
                    toggleIcon.classList.remove('bi-arrow-right-circle');
                    toggleIcon.classList.add('bi-arrow-left-circle');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
