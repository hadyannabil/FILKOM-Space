<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') – FILKOM Space</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        
        .admin-sidebar {
            background: linear-gradient(180deg, #0A1628 0%, #0f1e38 100%);
            width: 210px;
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.25s ease;
        }
        .admin-content {
            margin-left: 210px;
            min-height: 100vh;
            background: #f4f6fb;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 35;
        }
        .sidebar-overlay.active { display: block; }

        /* Mobile hamburger button */
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 8px;
            color: #0A1628;
            transition: background 0.15s;
        }
        .mobile-menu-btn:hover { background: #f3f4f6; }
        .mobile-menu-btn svg { width: 22px; height: 22px; }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 50;
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: flex;
            }
            .topbar {
                padding: 0 16px !important;
            }
            .page-header {
                padding: 20px 16px 0 !important;
            }
            .page-body {
                padding: 16px !important;
            }
            .topbar h1 {
                font-size: 1.05rem !important;
            }
            .topbar-right-date {
                display: none;
            }
            /* Scrollable tables on mobile */
            .table-scroll-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table-scroll-wrapper table {
                min-width: 580px;
            }
            /* Dashboard stat grid: 1 col on very small screens */
            .stat-grid-3 {
                grid-template-columns: 1fr !important;
            }
            /* Filter/search row stack on mobile */
            .filter-row {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            .filter-row > * { margin-left: 0 !important; }
            .filter-row input { width: 100% !important; box-sizing: border-box; }
            /* Table card header stack on mobile */
            .card-header-row {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }
            .card-header-row input { width: 100% !important; box-sizing: border-box; }
        }
        @media (min-width: 480px) and (max-width: 768px) {
            /* 3-col grid fits from 480px+ on mobile */
            .stat-grid-3 {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            color: #9baac4;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.18s ease;
            text-decoration: none;
            cursor: pointer;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .stat-card { background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #eaecf5; }
        .badge-pending  { background: #fff8e6; color: #b07e00; border: 1px solid #f5d76a; }
        .badge-approved { background: #e8f9ee; color: #1a7c3e; border: 1px solid #7dd4a3; }
        .badge-rejected { background: #fdecea; color: #c0392b; border: 1px solid #f5a19a; }
        .badge-cancelled{ background: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; }

        .btn-approve { background: #16a34a; color: #fff; padding: 10px 22px; border-radius: 9px; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: background 0.18s; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject  { background: #fff; color: #dc2626; padding: 10px 22px; border-radius: 9px; font-weight: 600; font-size: 0.875rem; border: 1.5px solid #dc2626; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: all 0.18s; }
        .btn-reject:hover  { background: #fef2f2; }

        .review-btn { background: #D4AF37; color: #0A1628; padding: 6px 18px; border-radius: 7px; font-size: 0.8rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; transition: filter 0.18s; }
        .review-btn:hover { filter: brightness(90%); }

        .topbar { background: #fff; border-bottom: 1px solid #eaecf5; padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; }

        table { width: 100%; border-collapse: collapse; }
        thead th { font-size: 0.78rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; padding: 10px 14px; text-align: left; border-bottom: 1px solid #f0f1f5; }
        tbody tr { border-bottom: 1px solid #f8f9fc; transition: background 0.12s; }
        tbody tr:hover { background: #fafbff; }
        tbody td { padding: 13px 14px; font-size: 0.875rem; color: #374151; }

        .page-header { padding: 28px 32px 0; }
        .page-body   { padding: 24px 32px; }


        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #D4AF37; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; color: #0A1628; flex-shrink: 0; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="admin-sidebar" id="admin-sidebar">

    <div style="padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,#D4AF37,#f4c430);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <div style="width:34px;height:34px;background:linear-gradient(135deg,#D4AF37,#f4c430);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <img src="{{ asset('assets/admin/gedung2.png') }}" style="width:20px;height:20px;object-fit:contain;">
                </div>
            </div>
            <div style="flex:1;">
                <div style="color:#fff;font-weight:700;font-size:0.95rem;line-height:1;">FILKOM Space</div>
                <div style="color:#7a8ba8;font-size:0.7rem;margin-top:2px;">Admin Dashboard</div>
            </div>
            <button id="sidebar-close-btn" onclick="closeSidebar()" style="display:none;background:none;border:none;cursor:pointer;color:#9baac4;padding:4px;border-radius:6px;line-height:0;" aria-label="Close sidebar">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <nav style="flex:1;padding:14px 0;">
        <a href="{{ route('admin.dashboard') }}"
        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.approvals') }}"
        class="nav-item {{ request()->routeIs('admin.approvals*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Approvals
        </a>
        <a href="{{ route('admin.reports') }}"
        class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <polyline points="3 17 9 11 13 15 21 7"/>
                <polyline points="14 7 21 7 21 14"/>
            </svg>
            Reports
        </a>
    </nav>

    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;gap:10px;">
        <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
        <div style="flex:1;min-width:0;">
            <div style="color:#fff;font-size:0.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
            <div style="color:#7a8ba8;font-size:0.68rem;">Admin</div>
        </div>
    </div>
</aside>

<div class="admin-content">

    <div class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="mobile-menu-btn" id="sidebar-open-btn" onclick="openSidebar()" aria-label="Open menu">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 style="font-size:1.35rem;font-weight:700;color:#0A1628;margin:0;">@yield('page-title')</h1>
                <p style="font-size:0.8rem;color:#9baac4;margin:0;">@yield('page-subtitle')</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;">
            <div class="relative" id="admin-notif-wrapper">
                <div id="admin-notif-btn" class="cursor-pointer flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors">
                    <img src="{{ asset('assets/navbar/notif.webp') }}" style="width:32px;height:32px;object-fit:contain;">
                    @if ($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>

                <div id="admin-notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-[#0A1628]">Notifications</h3>
                        <span class="text-xs text-[#D4AF37] font-medium cursor-pointer hover:underline">Mark all read</span>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        @forelse ($notifications as $notif)
                            <div class="px-4 py-3 border-b border-gray-50 {{ $notif->is_read ? 'bg-white' : 'bg-blue-50/50' }} hover:bg-gray-50 cursor-pointer transition">
                                <p class="text-sm text-gray-800 font-semibold mb-1">{{ $notif->title }}</p>
                                <p class="text-xs text-gray-500 mb-2">{{ $notif->message }}</p>
                                <p class="text-[10px] text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm text-gray-500">No new notifications</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="px-4 py-2 border-t border-gray-100 text-center bg-gray-50">
                        <a href="#" class="text-xs font-semibold text-gray-500 hover:text-[#0A1628]">View All History</a>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const btn  = document.getElementById('admin-notif-btn');
                    const drop = document.getElementById('admin-notif-dropdown');
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        drop.classList.toggle('hidden');
                    });
                    document.addEventListener('click', function () {
                        drop.classList.add('hidden');
                    });
                    drop.addEventListener('click', function (e) {
                        e.stopPropagation();
                    });
                });
            </script>
            <div style="text-align:right;" class="topbar-right-date">
                <div style="font-size:0.8rem;font-weight:600;color:#374151;">Today</div>
                <div style="font-size:0.75rem;color:#9baac4;">{{ now()->format('F j, Y') }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;color:#6b7280;font-size:0.8rem;padding:6px 10px;border-radius:6px;transition:background 0.15s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div style="margin:16px 32px 0;background:#e8f9ee;border:1px solid #7dd4a3;border-radius:9px;padding:12px 18px;color:#1a7c3e;font-size:0.875rem;font-weight:500;">
        ✓ {{ session('success') }}
    </div>
    @endif

    @yield('content')
</div>

<script>
    function openSidebar() {
        document.getElementById('admin-sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('active');
    }
    function closeSidebar() {
        document.getElementById('admin-sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('active');
    }
    document.getElementById('sidebar-overlay').addEventListener('click', closeSidebar);

    // Show close button on mobile
    function checkMobile() {
        const closeBtn = document.getElementById('sidebar-close-btn');
        if (window.innerWidth <= 768) {
            closeBtn.style.display = 'block';
        } else {
            closeBtn.style.display = 'none';
            // ensure sidebar always visible on desktop
            document.getElementById('admin-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('active');
        }
    }
    checkMobile();
    window.addEventListener('resize', checkMobile);
</script>

</body>
</html>
