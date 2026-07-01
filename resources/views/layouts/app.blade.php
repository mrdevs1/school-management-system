<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ setting('school_name', 'বিদ্যাপীঠ') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        
    /* বাংলা সংখ্যার জন্য Noto Sans Bengali */
    @font-face {
        font-family: "BengaliMixed";
        src: local("Noto Sans Bengali");
        unicode-range: U+09E6-09EF; /* বাংলা সংখ্যা ০-৯ */
    }
    @font-face {
        font-family: "BengaliMixed";
        src: local("Hind Siliguri");
        unicode-range: U+0000-09E5, U+09F0-FFFF; /* বাকি সব */
    }

    :root {
            --green: #0f7a55; --green-light: #e6f4f0; --green-mid: #1a9e70;
            --bg: #f4f6f5; --card: #ffffff; --sidebar-bg: #0a2e22;
            --sidebar-active: rgba(26,158,112,0.35); --sidebar-text: rgba(255,255,255,0.75);
            --text: #1a2e28; --text-muted: #6b7f78; --border: #e0ebe7;
            --shadow: 0 2px 20px rgba(10,46,34,0.08); --radius: 14px; --radius-sm: 8px;
            --font-bn: 'Hind Siliguri', sans-serif; --font-en: 'DM Sans', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'BengaliMixed', 'Noto Sans Bengali', 'Hind Siliguri', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }
        /* Sidebar */
        .sidebar { width: 260px; background: var(--sidebar-bg); position: fixed; top: 0; left: 0; bottom: 0; display: flex; flex-direction: column; z-index: 100; transition: transform 0.3s ease; }
        .sidebar-brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 12px; }
        .brand-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #1a9e70, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .brand-text .name { color: #fff; font-weight: 700; font-size: 14px; line-height: 1.3; }
        .brand-text .tagline { color: rgba(255,255,255,0.4); font-size: 11px; font-family: var(--font-en); }
        .sidebar-nav { flex: 1; padding: 12px 10px; overflow-y: auto; overflow-x: hidden; }
        .nav-section-label { font-size: 10px; font-family: var(--font-en); text-transform: uppercase; letter-spacing: 1.2px; color: rgba(255,255,255,0.3); padding: 12px 12px 5px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 14px; border-radius: var(--radius-sm); color: var(--sidebar-text); text-decoration: none; font-size: 13.5px; font-weight: 500; margin-bottom: 2px; transition: all 0.18s; position: relative; }
        .nav-item i { font-size: 16px; width: 20px; text-align: center; }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: #fff; transform: translateX(3px); }
        .nav-item.active { background: var(--sidebar-active); color: #fff; font-weight: 600; }
        .nav-item.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 60%; background: #1a9e70; border-radius: 0 4px 4px 0; }
        .sidebar-footer { padding: 14px 10px; border-top: 1px solid rgba(255,255,255,0.08); }
        /* Main */
        .main-wrapper { margin-left: 260px; flex: 1; min-width: 0; display: flex; flex-direction: column; }
        /* Topbar */
        .topbar { background: var(--card); padding: 0 28px; height: 62px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; }
        .page-title { font-size: 17px; font-weight: 700; }
        .breadcrumb-trail { font-size: 12px; color: var(--text-muted); font-family: var(--font-en); }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-btn { width: 36px; height: 36px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: transparent; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); font-size: 16px; transition: all 0.18s; text-decoration: none; }
        .topbar-btn:hover { background: var(--green-light); color: var(--green); }
        .user-chip { display: flex; align-items: center; gap: 9px; padding: 5px 13px 5px 7px; border: 1px solid var(--border); border-radius: 40px; cursor: pointer; text-decoration: none; color: var(--text); background: var(--card); transition: all 0.18s; position: relative; }
        .user-chip:hover { border-color: var(--green); }
        .user-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #0f7a55, #059669); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 700; }
        .user-name { font-size: 13px; font-weight: 600; }
        .user-dropdown { position: absolute; top: calc(100% + 8px); right: 0; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-sm); min-width: 175px; box-shadow: var(--shadow); display: none; z-index: 200; overflow: hidden; }
        .user-chip:hover .user-dropdown { display: block; }
        .dropdown-item { display: flex; align-items: center; gap: 9px; padding: 10px 16px; font-size: 13px; color: var(--text); text-decoration: none; transition: background 0.15s; font-family: var(--font-bn); }
        .dropdown-item:hover { background: var(--bg); }
        .dropdown-item.danger { color: #e53e3e; }
        /* Content */
        .page-content { padding: 26px; flex: 1; }
        /* Cards */
        .card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); }
        .card-header { padding: 16px 20px 0; display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14.5px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .card-title i { color: var(--green); }
        .card-body { padding: 16px 20px 20px; }
        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { padding: 18px 20px; border-radius: var(--radius); position: relative; overflow: hidden; color: #fff; }
        .stat-card .icon { font-size: 24px; margin-bottom: 12px; }
        .stat-card .value { font-size: 26px; font-weight: 700; line-height: 1; margin-bottom: 5px; }
        .stat-card .label { font-size: 12.5px; opacity: 0.8; }
        .stat-green  { background: linear-gradient(135deg, #0f7a55, #059669); }
        .stat-blue   { background: linear-gradient(135deg, #1e40af, #3b82f6); }
        .stat-orange { background: linear-gradient(135deg, #b45309, #f59e0b); }
        .stat-rose   { background: linear-gradient(135deg, #9f1239, #f43f5e); }
        /* Table */
        .table-wrap { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table thead th { background: #f0f4f2; color: var(--text); font-weight: 600; padding: 10px 15px; text-align: left; border-bottom: 2px solid var(--border); font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; font-family: var(--font-en); white-space: nowrap; }
        .data-table tbody td { padding: 10px 15px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .data-table tbody tr:hover { background: var(--green-light); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; font-family: var(--font-en); }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-orange { background: #fef3c7; color: #b45309; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: var(--font-bn); cursor: pointer; border: none; transition: all 0.18s; text-decoration: none; }
        .btn-primary { background: var(--green); color: #fff; }
        .btn-primary:hover { background: var(--green-mid); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15,122,85,0.35); color: #fff; }
        .btn-outline { background: transparent; color: var(--green); border: 1.5px solid var(--green); }
        .btn-outline:hover { background: var(--green-light); color: var(--green); }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fca5a5; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; border-radius: var(--radius-sm); display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
        /* Forms */
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px; }
        .form-control, .form-select { width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13.5px; font-family: var(--font-bn); color: var(--text); background: #fff; transition: border-color 0.2s; outline: none; -webkit-appearance: none; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(15,122,85,0.1); }
        .form-grid { display: grid; gap: 16px; }
        .form-grid-2 { grid-template-columns: 1fr 1fr; }
        .form-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        /* Alerts */
        .alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13.5px; margin-bottom: 18px; }
        .alert-success { background: #dcfce7; color: #15803d; border-left: 4px solid #16a34a; }
        .alert-error   { background: #fee2e2; color: #dc2626; border-left: 4px solid #dc2626; }
        .alert-warning { background: #fef3c7; color: #b45309; border-left: 4px solid #f59e0b; }
        /* Misc */
        .filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .filter-bar .form-control, .filter-bar .form-select { width: auto; min-width: 140px; }
        .avatar-placeholder { width: 36px; height: 36px; border-radius: 50%; background: var(--green-light); color: var(--green); display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
        .student-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); }
        .progress-bar-wrap { height: 6px; background: var(--border); border-radius: 20px; overflow: hidden; }
        .progress-bar-fill { height: 100%; border-radius: 20px; background: var(--green); }
        .empty-state { text-align: center; padding: 50px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 44px; color: var(--border); display: block; margin-bottom: 12px; }
        .pagination-wrap { padding: 14px 20px; border-top: 1px solid var(--border); }
        .tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--border); margin-bottom: 20px; }
        .tab-btn { padding: 8px 16px; font-size: 13px; font-weight: 600; font-family: var(--font-bn); color: var(--text-muted); background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.18s; }
        .tab-btn.active { color: var(--green); border-bottom-color: var(--green); }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: var(--card); border-radius: var(--radius); max-width: 580px; width: 95%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.2); }
        .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-body { padding: 20px 22px; }
        .modal-footer { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; }
        
        /* ===== Mobile Responsive ===== */
        @media (max-width: 768px) {
            /* Forms */
            .form-grid-2,
            .form-grid-3 { grid-template-columns: 1fr !important; }

            /* All inline grids */
            [style*="grid-template-columns: 1fr 1fr"],
            [style*="grid-template-columns:1fr 1fr"],
            [style*="grid-template-columns: 1fr 2fr"],
            [style*="grid-template-columns: 2fr 1fr"],
            [style*="grid-template-columns: 1fr 380px"],
            [style*="grid-template-columns: 1fr 360px"],
            [style*="grid-template-columns: 1fr 340px"],
            [style*="grid-template-columns: 1fr 320px"],
            [style*="grid-template-columns: 1fr 300px"],
            [style*="grid-template-columns:1fr 300px"],
            [style*="grid-template-columns: 280px 1fr"],
            [style*="grid-template-columns: 300px 1fr"],
            [style*="grid-template-columns: 320px 1fr"],
            [style*="grid-template-columns: 1.5fr 1fr"],
            [style*="grid-template-columns: 1fr 1.5fr"] {
                grid-template-columns: 1fr !important;
            }

            /* Stats cards - 2 column on mobile */
            [style*="grid-template-columns: repeat(4,1fr)"],
            [style*="grid-template-columns:repeat(4,1fr)"],
            [style*="grid-template-columns: repeat(4, 1fr)"],
            [style*="grid-template-columns: repeat(3,1fr)"],
            [style*="grid-template-columns:repeat(3,1fr)"],
            [style*="grid-template-columns: repeat(3, 1fr)"] {
                grid-template-columns: 1fr 1fr !important;
            }

            /* Page content padding */
            .page-content { padding: 12px !important; }

            /* Cards */
            .card-body { padding: 14px 16px !important; }
            .card-header { padding: 12px 16px 0 !important; }

            /* Filter bar */
            .filter-bar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 8px !important;
            }
            .filter-bar .form-control,
            .filter-bar .form-select,
            .filter-bar .btn {
                width: 100% !important;
                min-width: unset !important;
            }

            /* Tables - horizontal scroll */
            .table-wrap { overflow-x: auto !important; }
            .data-table { min-width: 600px; }

            /* Topbar */
            .topbar { padding: 0 14px !important; }
            .page-title { font-size: 15px !important; }
            .breadcrumb-trail { display: none; }

            /* User chip */
            .user-name { max-width: 80px !important; }

            /* Buttons in topbar */
            .topbar-right { gap: 6px !important; }

            /* Modal */
            .modal-box { width: 95% !important; margin: 10px !important; }

            /* Pagination */
            .pagination-wrap { padding: 10px 14px !important; }

            /* Stats grid fix */
            .stats-grid { grid-template-columns: 1fr 1fr !important; gap: 10px !important; }

            /* Span 2 columns reset on mobile */
            [style*="grid-column: span 2"],
            [style*="grid-column:span 2"] {
                grid-column: span 1 !important;
            }

            /* Sticky position reset on mobile */
            [style*="position: sticky"],
            [style*="position:sticky"] {
                position: relative !important;
                top: auto !important;
            }

            /* Salary/Fee table adjustments */
            .btn-sm { padding: 4px 8px !important; font-size: 11px !important; }
            .badge { font-size: 10.5px !important; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns: 1fr 1fr"],
            [style*="grid-template-columns:1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        .responsive-grid { display: grid; }
        @media (max-width: 768px) {
            .responsive-grid { grid-template-columns: 1fr !important; }
        }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
        @media (max-width: 768px) { .sidebar { width: 260px; background: var(--sidebar-bg); position: fixed; top: 0; left: 0; bottom: 0; display: flex; flex-direction: column; z-index: 100; transition: transform 0.3s ease; } .sidebar.open { transform: translateX(0); } .main-wrapper { margin-left: 0; } .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
        .responsive-grid { grid-template-columns: 1fr !important; }
        [class*="grid"] > * { min-width: 0; }
    }
        @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; } }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99; }
        #sidebarToggle { display: none; }
        @media (max-width: 1024px) {
            #sidebarToggle { display: flex !important; }
            .sidebar { transform: translateX(-260px) !important; }
            .sidebar.open { transform: translateX(0) !important; }
            .main-wrapper { margin-left: 0 !important; }
        }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99; }
        #sidebarToggle { display: none; }
        @media (max-width: 1024px) {
            #sidebarToggle { display: flex !important; }
            .sidebar { transform: translateX(-260px) !important; }
            .sidebar.open { transform: translateX(0) !important; }
            .main-wrapper { margin-left: 0 !important; }
        }
        @media print { .sidebar, .topbar, .no-print { display: none !important; } .main-wrapper { margin: 0; } }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🕌</div>
        <div class="brand-text">
            <div class="name">{{ setting('school_name', 'বিদ্যাপীঠ') }}</div>
            <div class="tagline">Management System</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> ড্যাশবোর্ড
        </a>
        <div class="nav-section-label">Academic</div>
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> ছাত্র-ছাত্রী
        </a>
        <a href="{{ route('teachers.index') }}" class="nav-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> শিক্ষক ও কর্মচারী
        </a>
        <a href="{{ route('designations.index') }}" class="nav-item {{ request()->routeIs('designations.*') ? 'active' : '' }}">
            <i class="bi bi-award"></i> পদবি যোগ করুন
        </a>
        <a href="{{ route('classes.index') }}" class="nav-item {{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> শ্রেণী ও বিভাগ
        </a>
        <a href="{{ route('subjects.index') }}" class="nav-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i> বিষয়সমূহ
        </a>
        <div class="nav-section-label">Daily</div>
        <a href="{{ route('attendance.index') }}" class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> উপস্থিতি
        </a>
        <a href="{{ route('notices.index') }}" class="nav-item {{ request()->routeIs('notices.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i> নোটিশ বোর্ড
        </a>
        <div class="nav-section-label">Examination</div>
        <a href="{{ route('exams.index') }}" class="nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
            <i class="bi bi-pencil-square"></i> পরীক্ষাসমূহ
        </a>
        <a href="{{ route('results.index') }}" class="nav-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i> ফলাফল
        </a>
        <div class="nav-section-label">Special</div>
        <a href="{{ route('meals.index') }}" class="nav-item {{ request()->routeIs('meals.*') ? 'active' : '' }}">
            <i class="bi bi-cup-hot-fill"></i> খোরাকি
        </a>
        <a href="{{ route('welfare.index') }}" class="nav-item {{ request()->routeIs('welfare.*') ? 'active' : '' }}">
            <i class="bi bi-heart-fill"></i> গোরাবা ফান্ড
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> রিপোর্ট
        </a>
        <div class="nav-section-label">Finance</div>
        <a href="{{ route('fees.index') }}" class="nav-item {{ request()->routeIs('fees.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> বেতন ও ফি
        </a>
        <a href="{{ route('fee-categories.index') }}" class="nav-item {{ request()->routeIs('fee-categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> ফি ক্যাটাগরি
        </a>
        <a href="{{ route('salaries.index') }}" class="nav-item {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> বেতন প্রদান
        </a>

    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}" style="margin-bottom:6px;">
            <i class="bi bi-gear-fill"></i> সেটিংস
        </a>
        <div class="nav-item" style="margin:0; cursor:default;">
            <i class="bi bi-calendar2-check" style="color:#1a9e70;"></i>
            <div>
                <div style="font-size:11px; color:rgba(255,255,255,0.5); font-family:var(--font-en);">Session</div>
                <div style="font-size:12.5px; color:#fff; font-weight:600;">{{ $currentSession->name ?? '—' }}</div>
            </div>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrapper">
    <header class="topbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <button class="topbar-btn" id="sidebarToggle" style="border:none; cursor:pointer;">
                <i class="bi bi-list" style="font-size:20px;"></i>
            </button>
            <div>
                <div class="page-title">@yield('page-title', 'ড্যাশবোর্ড')</div>
                <div class="breadcrumb-trail">@yield('breadcrumb', 'Home')</div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('attendance.index') }}" class="topbar-btn" title="উপস্থিতি">
                <i class="bi bi-calendar-check"></i>
            </a>
            <a href="{{ route('students.create') }}" class="topbar-btn" title="নতুন ভর্তি">
                <i class="bi bi-person-plus"></i>
            </a>
            <div class="user-chip">
                <div class="user-avatar">{{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                <span class="user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down" style="font-size:10px; color:var(--text-muted);"></i>
                <div class="user-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="bi bi-person"></i> প্রোফাইল
                    </a>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">
                        <i class="bi bi-gear"></i> সেটিংস
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                            <i class="bi bi-box-arrow-right"></i> লগআউট
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script>
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        if (overlay) overlay.style.display = 'block';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        if (overlay) overlay.style.display = 'none';
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
    }
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) closeSidebar();
    });
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>
