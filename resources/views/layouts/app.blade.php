<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Post Approval System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%); box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 8px; margin: 4px 0; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.15); color: #fff; }
        .sidebar .nav-link i { width: 25px; }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .card-header { background: #fff; border-bottom: 2px solid #f0f0f0; font-weight: 600; padding: 15px 20px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 10px 25px; border-radius: 8px; }
        .btn-primary:hover { background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%); }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .role-badge { padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; text-transform: uppercase; }
        .role-author { background: #e3f2fd; color: #0d47a1; }
        .role-manager { background: #f3e5f5; color: #4a148c; }
        .role-admin { background: #ffebee; color: #b71c1c; }
        .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .auth-card { width: 100%; max-width: 450px; border-radius: 16px; overflow: hidden; }
        .table-responsive { border-radius: 12px; overflow: hidden; }
        .table thead th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .action-btns .btn { padding: 5px 10px; font-size: 0.85rem; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }
        .navbar { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .page-header { margin-bottom: 30px; }
        .page-header h2 { font-weight: 600; color: #2c3e50; }
        .modal-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        .modal-header .btn-close { filter: invert(1); }
        .form-label { font-weight: 500; color: #495057; }
        .error-text { color: #dc3545; font-size: 0.875rem; margin-top: 4px; }
        .is-invalid { border-color: #dc3545 !important; }
        .sidebar-brand { padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar-brand h4 { color: #fff; margin: 0; font-weight: 600; }
        .sidebar-brand small { color: rgba(255,255,255,0.6); }
        .menu-section { padding: 0 10px; margin-top: 20px; }
        .menu-section-title { color: rgba(255,255,255,0.4); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 10px 15px; margin: 0; }
        .dropdown-menu { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
    </style>
    @yield('styles')
</head>
<body>
    <div id="appLayout" style="display:none;">
        <div class="container-fluid">
            <div class="row">
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse show" id="sidebarMenu">
                    <div class="position-sticky pt-3">
                        <div class="sidebar-brand text-center">
                            <h4><i class="fas fa-shield-alt"></i> Post Approval</h4>
                            <small>Management System</small>
                        </div>
                        <div class="menu-section">
                            <p class="menu-section-title">Main Menu</p>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('posts.index') }}">
                                        <i class="fas fa-file-alt"></i> All Posts
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="showAllLogs()">
                                        <i class="fas fa-history"></i> Activity Logs
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="menu-section" id="managerMenu" style="display:none;">
                            <p class="menu-section-title">Reviewer Menu</p>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('posts.pending') }}">
                                        <i class="fas fa-clock"></i> Pending Reviews
                                        <span class="badge bg-warning float-end" id="pendingCount">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="menu-section" id="adminMenu" style="display:none;">
                            <p class="menu-section-title">Admin Menu</p>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-users"></i> User Management
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-cogs"></i> Settings
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded mb-4">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                            <div class="user-info">
                                                <div class="user-avatar" id="userAvatar">U</div>
                                                <span id="userName">User</span>
                                                <span class="role-badge" id="userRole">-</span>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <div id="authLayout">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        function getToken() { return localStorage.getItem('token'); }
        function setToken(token) { localStorage.setItem('token', token); }
        function removeToken() { localStorage.removeItem('token'); }
        function isAuthenticated() { return !!getToken(); }
        function getUser() { const user = localStorage.getItem('user'); return user ? JSON.parse(user) : null; }
        function setUser(user) { localStorage.setItem('user', JSON.stringify(user)); }
        function removeUser() { localStorage.removeItem('user'); }

        function initLayout() {
            const user = getUser();
            if (isAuthenticated() && user) {
                $('#appLayout').show();
                $('#authLayout').hide();
                $('#userName').text(user.name);
                $('#userRole').text(user.role);
                $('#userRole').addClass('role-' + user.role);
                $('#userAvatar').text(user.name.charAt(0).toUpperCase());
                
                if (user.role === 'manager' || user.role === 'admin') {
                    $('#managerMenu').show();
                    loadPendingCount();
                }
                if (user.role === 'admin') {
                    $('#adminMenu').show();
                }
            } else {
                $('#appLayout').hide();
                $('#authLayout').show();
            }
        }

        function loadPendingCount() {
            $.ajax({
                url: '/api/posts',
                method: 'GET',
                success: function(response) {
                    const pending = response.data.filter(p => p.status === 'pending').length;
                    $('#pendingCount').text(pending);
                }
            });
        }

        function logout() {
            $.ajax({
                url: '/api/auth/logout',
                method: 'POST',
                success: function(response) {
                    removeToken();
                    removeUser();
                    Swal.fire({ icon: 'success', title: 'Logged Out', text: 'You have been logged out successfully.', timer: 1500, showConfirmButton: false }).then(() => { window.location.href = '/login'; });
                },
                error: function(xhr) {
                    removeToken();
                    removeUser();
                    window.location.href = '/login';
                }
            });
        }

        function showSwal(icon, title, text) { Swal.fire({ icon: icon, title: title, text: text, timer: 2000, showConfirmButton: false }); }
        function showConfirmSwal(title, text, confirmText, callback) {
            Swal.fire({ title: title, text: text, icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: confirmText }).then((result) => { if (result.isConfirmed) { callback(); } });
        }

        $(document).ready(function() { initLayout(); });
    </script>
    @yield('scripts')
</body>
</html>
