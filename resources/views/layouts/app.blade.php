<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Personal Task Manager') — WST21</title>
    <style>
        :root {
            --primary: #c87e61;
            --primary-hover: #ab6349;
            --primary-light: #fbede7;
            --secondary: #64748b;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #0ea5e9;
            --info-light: #f0f9ff;
            --sidebar-bg: #352822;
            --sidebar-text: #d8c6bc;
            --sidebar-active: #4a3730;
            --bg: #faf7f5;
            --card-bg: #ffffff;
            --text-main: #332720;
            --text-muted: #786a63;
            --border-color: #eadfd9;
            --font-ui: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --text-xs: 0.75rem;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.375rem;
            --weight-medium: 500;
            --weight-semibold: 600;
            --weight-bold: 700;
            --radius-lg: 14px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-ui);
            font-size: var(--text-base);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            display: block;
            object-fit: contain;
        }

        .brand-title {
            color: #ffffff;
            font-size: var(--text-base);
            font-weight: var(--weight-bold);
            letter-spacing: -0.015em;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: var(--text-xs);
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.06em;
            line-height: 1.35;
        }

        .sidebar-nav {
            padding: 20px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
            font-weight: 700;
            padding: 12px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            color: #94a3b8;
            font-size: 0.92rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: #ffffff;
            background-color: var(--sidebar-active);
        }

        .nav-item.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.14);
            font-weight: 600;
        }

        .nav-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(24, 24, 27, 0.5) 100%);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.2s ease;
        }

        .nav-icon-badge svg {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
            color: #ffffff;
            opacity: 1 !important;
        }

        .nav-item:hover .nav-icon-badge,
        .nav-item.active .nav-icon-badge {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(24, 24, 27, 0.7) 100%);
            border-color: rgba(255, 255, 255, 0.35);
        }

        /* Floating Action Button (FAB) */
        .fab-add-task {
            position: fixed;
            bottom: 30px;
            right: 32px;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c87e61, #9f5f49);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(200, 126, 97, 0.45);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        }

        .fab-add-task:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 10px 26px rgba(200, 126, 97, 0.6);
        }

        .fab-add-task:active {
            transform: scale(0.95);
        }

        .fab-add-task svg {
            width: 26px;
            height: 26px;
            stroke-width: 2.4;
        }

        /* Priority Radio Pill Buttons */
        .priority-radios {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .priority-radio-label {
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .priority-radio-label input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .priority-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 4px;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            background: #ffffff;
            font-size: 0.76rem;
            font-weight: 700;
            color: #64748b;
            transition: all 0.18s ease;
            text-align: center;
        }

        .priority-card svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.2;
        }

        .priority-radio-label input:checked + .priority-card.p-low {
            border-color: #64748b;
            background: #f1f5f9;
            color: #1e293b;
            box-shadow: 0 0 0 2px rgba(100, 116, 139, 0.2);
        }

        .priority-radio-label input:checked + .priority-card.p-medium {
            border-color: #6366f1;
            background: #eef2ff;
            color: #4338ca;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
        }

        .priority-radio-label input:checked + .priority-card.p-high {
            border-color: #f97316;
            background: #fff7ed;
            color: #c2410c;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.25);
        }

        .priority-radio-label input:checked + .priority-card.p-urgent {
            border-color: #ef4444;
            background: #fef2f2;
            color: #b91c1c;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.28);
        }

        .sidebar-user {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.15);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c87e61, #9f5f49);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .user-meta {
            overflow: hidden;
        }

        .user-name {
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP NAVBAR ===== */
        .topbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-title {
            font-size: var(--text-xl);
            font-weight: var(--weight-bold);
            color: var(--text-main);
            letter-spacing: -0.025em;
            line-height: 1.2;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            line-height: 1.3;
        }

        .btn svg { width: 16px; height: 16px; stroke-width: 2; }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(200, 126, 97, 0.24);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #ffffff;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #f1f5f9;
        }

        .btn-success {
            background-color: var(--success);
            color: #ffffff;
        }
        .btn-success:hover { background-color: #059669; }

        .btn-warning {
            background-color: var(--warning);
            color: #ffffff;
        }
        .btn-warning:hover { background-color: #d97706; }

        .btn-danger {
            background-color: var(--danger);
            color: #ffffff;
        }
        .btn-danger:hover { background-color: #dc2626; }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.82rem;
            border-radius: var(--radius-sm);
        }

        .btn-icon {
            padding: 8px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-muted);
            cursor: pointer;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        /* ===== CONTENT AREA ===== */
        .content {
            padding: 28px 32px 40px;
            flex: 1;
        }

        .view-shell {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-val {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        /* Status badges */
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-in-progress { background-color: #e0f2fe; color: #0369a1; }
        .badge-completed { background-color: #d1fae5; color: #065f46; }

        /* Priority badges */
        .badge-low { background-color: #f1f5f9; color: #475569; }
        .badge-medium { background-color: #e0e7ff; color: #3730a3; }
        .badge-high { background-color: #ffedd5; color: #c2410c; }
        .badge-urgent { background-color: #fee2e2; color: #991b1b; }

        /* Category badge */
        .badge-category {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid var(--border-color);
        }

        /* ===== FORMS ===== */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text-main);
            background: #ffffff;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.82rem;
            margin-top: 4px;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.92rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background-color: var(--success-light);
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background-color: var(--danger-light);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-container {
            background: #ffffff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 580px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: modalPop 0.2s ease-out;
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 1.15rem;
            font-weight: 700;
        }

        .modal-body {
            padding: 24px;
            max-height: 75vh;
            overflow-y: auto;
        }

        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* ===== PROGRESS BAR ===== */
        .progress-bar-bg {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--success);
            border-radius: 10px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .brand-title,
            .sidebar .brand-sub,
            .sidebar .nav-section-title,
            .sidebar .nav-text,
            .sidebar .user-meta {
                display: none;
            }
            .main-wrapper {
                margin-left: 70px;
            }
            .sidebar-brand {
                justify-content: center;
                padding: 16px 0;
            }
            .nav-item {
                justify-content: center;
                padding: 12px 0;
            }
            .sidebar-user {
                justify-content: center;
                padding: 12px 0;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 18px;
            }
            .topbar {
                padding: 0 18px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img class="brand-icon" src="{{ asset('images/tala.svg') }}" alt="Tala logo">
            <div>
                <div class="brand-title">TaskManager</div>
                <div class="brand-sub">WST21 • Christian Romano</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Views & Dashboards</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon-badge">
                    <i data-lucide="layout-dashboard" aria-hidden="true"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                <div class="nav-icon-badge">
                    <i data-lucide="list-todo" aria-hidden="true"></i>
                </div>
                <span class="nav-text">All Tasks</span>
            </a>
            <a href="{{ route('tasks.board') }}" class="nav-item {{ request()->routeIs('tasks.board') ? 'active' : '' }}">
                <div class="nav-icon-badge">
                    <i data-lucide="columns-3" aria-hidden="true"></i>
                </div>
                <span class="nav-text">Task Board</span>
            </a>
            <a href="{{ route('calendar') }}" class="nav-item {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <div class="nav-icon-badge">
                    <i data-lucide="calendar-days" aria-hidden="true"></i>
                </div>
                <span class="nav-text">Calendar</span>
            </a>

            <div class="nav-section-title">Account</div>
            <a href="{{ route('settings.account') }}" class="nav-item {{ request()->routeIs('settings.account') ? 'active' : '' }}">
                <div class="nav-icon-badge">
                    <i data-lucide="settings-2" aria-hidden="true"></i>
                </div>
                <span class="nav-text">Account Settings</span>
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-meta">
                    <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="user-role">{{ '@' . (Auth::user()->username ?? 'student') }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-icon" title="Logout" style="background: transparent; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer;">
                    <i data-lucide="log-out" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="page-title">@yield('header_title', 'Dashboard')</h1>
            </div>
        </header>

        <main class="content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success" id="flash-msg">
                    <span>{{ session('success') }}</span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" id="flash-msg">
                    <span>{{ session('error') }}</span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div>
                        <strong>Please resolve the following:</strong>
                        <ul style="margin: 6px 0 0 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Floating Action Button for Adding Tasks -->
    <button type="button" class="fab-add-task" onclick="openCreateTaskModal()" title="Add New Task" aria-label="Add New Task">
        <i data-lucide="plus" aria-hidden="true"></i>
    </button>

    <!-- Quick Create Task Modal -->
    <div class="modal-overlay" id="createTaskModal">
        <div class="modal-container">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('images/tala.svg') }}" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
                    <h3 style="font-size: 1.15rem; font-weight: 750; color: #1e293b; margin: 0;">Create New Task</h3>
                </div>
                <button type="button" class="btn-icon" onclick="closeCreateTaskModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="m_task_name">Task Name <span style="color:red;">*</span></label>
                        <input type="text" name="task_name" id="m_task_name" class="form-control" placeholder="What needs to be done?" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="m_description">Description</label>
                        <textarea name="description" id="m_description" class="form-control" rows="3" placeholder="Add optional details, notes, or code snippet..."></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div class="form-group">
                            <label class="form-label" for="m_category_name">Category</label>
                            <input type="text" name="new_category_name" id="m_category_name" class="form-control" list="existingCategoriesList" placeholder="Choose or type a category...">
                            <datalist id="existingCategoriesList">
                                @foreach(Auth::user()->categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="m_due_date">Due Date</label>
                            <input type="date" name="due_date" id="m_due_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Priority Level</label>
                        <div class="priority-radios">
                            <label class="priority-radio-label">
                                <input type="radio" name="priority" value="Low">
                                <div class="priority-card p-low">
                                    <i data-lucide="arrow-down" aria-hidden="true"></i>
                                    <span>Low</span>
                                </div>
                            </label>
                            <label class="priority-radio-label">
                                <input type="radio" name="priority" value="Medium" checked>
                                <div class="priority-card p-medium">
                                    <i data-lucide="minus" aria-hidden="true"></i>
                                    <span>Medium</span>
                                </div>
                            </label>
                            <label class="priority-radio-label">
                                <input type="radio" name="priority" value="High">
                                <div class="priority-card p-high">
                                    <i data-lucide="arrow-up" aria-hidden="true"></i>
                                    <span>High</span>
                                </div>
                            </label>
                            <label class="priority-radio-label">
                                <input type="radio" name="priority" value="Urgent">
                                <div class="priority-card p-urgent">
                                    <i data-lucide="alert-circle" aria-hidden="true"></i>
                                    <span>Urgent</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="m_status">Initial Status</label>
                        <select name="status" id="m_status" class="form-control">
                            <option value="Pending" selected>Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subtasks (Optional)</label>
                        <div id="subtaskInputs">
                            <input type="text" name="subtasks[]" class="form-control" style="margin-bottom: 8px;" placeholder="Subtask 1">
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addSubtaskInput()">+ Add Another Subtask</button>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="closeCreateTaskModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateTaskModal() {
            document.getElementById('createTaskModal').classList.add('active');
            window.lucide?.createIcons({ attrs: { 'stroke-width': 2 } });
        }

        function closeCreateTaskModal() {
            document.getElementById('createTaskModal').classList.remove('active');
        }

        function addSubtaskInput() {
            const container = document.getElementById('subtaskInputs');
            const count = container.querySelectorAll('input').length + 1;
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subtasks[]';
            input.className = 'form-control';
            input.style.marginBottom = '8px';
            input.placeholder = 'Subtask ' + count;
            container.appendChild(input);
        }

        // Auto hide flash
        setTimeout(() => {
            const flash = document.getElementById('flash-msg');
            if(flash) {
                flash.style.opacity = '0';
                flash.style.transition = 'opacity 0.4s ease';
                setTimeout(() => flash.remove(), 400);
            }
        }, 4000);
    </script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>
        window.lucide?.createIcons({ attrs: { 'stroke-width': 1.8 } });
    </script>
    @yield('scripts')
</body>
</html>
