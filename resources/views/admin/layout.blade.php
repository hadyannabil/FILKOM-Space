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
        }
        .admin-content {
            margin-left: 210px;
            min-height: 100vh;
            background: #f4f6fb;
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

        .notif-bell { position: relative; }
        .notif-count { position: absolute; top: -4px; right: -4px; background: #ef4444; color: #fff; border-radius: 50%; font-size: 0.65rem; font-weight: 700; width: 17px; height: 17px; display: flex; align-items: center; justify-content: center; }

        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #D4AF37; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; color: #0A1628; flex-shrink: 0; }
    </style>
</head>
<body>

<aside class="admin-sidebar">

    <div style="padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,#D4AF37,#f4c430);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg fill="#0A1628" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path d="M3 21h18V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14z"/>
                    <path fill="#D4AF37" d="M7 9h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zM7 13h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zM9 17h6v4H9z"/>
                </svg>
            </div>
            <div>
                <div style="color:#fff;font-weight:700;font-size:0.95rem;line-height:1;">FILKOM Space</div>
                <div style="color:#7a8ba8;font-size:0.7rem;margin-top:2px;">Admin Dashboard</div>
            </div>
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
        <a href="#"
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
        <div>
            <h1 style="font-size:1.35rem;font-weight:700;color:#0A1628;margin:0;">@yield('page-title')</h1>
            <p style="font-size:0.8rem;color:#9baac4;margin:0;">@yield('page-subtitle')</p>
        </div>
        <div style="display:flex;align-items:center;gap:18px;">
            <div class="notif-bell">
                <svg fill="none" stroke="#6b7280" stroke-width="1.8" viewBox="0 0 24 24" style="width:22px;height:22px;cursor:pointer;">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                <span class="notif-count">3</span>
            </div>
            <div style="text-align:right;">
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

</body>
</html>
