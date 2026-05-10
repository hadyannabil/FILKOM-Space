@props([
    'logoAsset' => 'assets/navbar/logo.webp',
    'notifAsset' => 'assets/navbar/notif.webp', 
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FILKOM Space</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Mobile Drawer ── */
        .mobile-drawer {
            position: fixed;
            inset: 0;
            z-index: 200;
            pointer-events: none;
        }
        .mobile-drawer.open { pointer-events: all; }

        .drawer-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .mobile-drawer.open .drawer-overlay { opacity: 1; }

        .drawer-panel {
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 280px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .mobile-drawer.open .drawer-panel { transform: translateX(0); }

        .drawer-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 20px;
            color: #4b5563;
            font-size: 0.925rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            transition: background 0.15s, color 0.15s;
        }
        .drawer-nav-item:hover,
        .drawer-nav-item.active { background: #f3f4f6; color: #0A1628; }
        .drawer-nav-item.active { background: #EFF6FF; color: #0A1628; font-weight: 600; }
        .drawer-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .drawer-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            padding: 14px 20px 6px;
        }

        .drawer-filter-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            color: #374151;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .drawer-filter-label input { accent-color: #D4AF37; }

        /* Hamburger button */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px; height: 40px;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 8px;
            color: #0A1628;
            transition: background 0.15s;
        }
        .hamburger-btn:hover { background: #f3f4f6; }
        .hamburger-btn svg { width: 22px; height: 22px; }

        @media (max-width: 768px) {
            .hamburger-btn { display: flex; }
            /* Hide desktop aside filter on mobile - drawer handles it */
            .desktop-filter-aside { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 font-inter">

    <div class="mobile-drawer" id="mobile-drawer">
        <div class="drawer-overlay" id="drawer-overlay"></div>
        <div class="drawer-panel" id="drawer-panel">

            <div style="padding:20px 20px 16px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <img src="{{ asset($logoAsset) }}" style="height:32px;width:auto;object-fit:contain;">
                    <span style="color:#0A1628;font-weight:700;font-size:0.95rem;">FILKOM Space</span>
                </div>
                <button onclick="closeDrawer()" style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;border-radius:6px;line-height:0;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <div style="padding:12px 0;">
                <a href="/" class="drawer-nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <a href="/history" class="drawer-nav-item {{ request()->is('history') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    My Bookings
                </a>
            </div>

            <div id="drawer-filters-section" style="border-top:1px solid #e5e7eb;padding:12px 0;">
                <div class="drawer-section-title">Filter Ruangan</div>

                <form action="{{ route('dashboard') }}" method="GET" id="drawer-filter-form">
                    <input type="hidden" name="date" id="df-date">
                    <input type="hidden" name="start_time" id="df-start">
                    <input type="hidden" name="end_time" id="df-end">
                    <input type="hidden" name="room_filter" id="df-room">

                    <div style="padding:4px 0 10px;">
                        <div class="drawer-section-title" style="padding-top:6px;">Gedung</div>
                        @foreach (['A Building' => 'Gedung A', 'F Building' => 'Gedung F', 'G Building' => 'Gedung G', 'GKM Building' => 'Gedung GKM'] as $value => $label)
                        <label class="drawer-filter-label">
                            <input type="checkbox" name="buildings[]" value="{{ $value }}"
                                   {{ in_array($value, request()->get('buildings', [])) ? 'checked' : '' }}
                                   onchange="document.getElementById('drawer-filter-form').submit()">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>

                    <div style="padding:4px 0 10px;border-top:1px solid #f3f4f6;">
                        <div class="drawer-section-title" style="padding-top:10px;">Kapasitas</div>
                        @foreach (['1-50' => '1-50 orang', '51-100' => '51-100 orang', '101-200' => '101-200 orang'] as $val => $label)
                        <label class="drawer-filter-label">
                            <input type="radio" name="capacity" value="{{ $val }}"
                                   {{ request('capacity') === $val ? 'checked' : '' }}
                                   onchange="document.getElementById('drawer-filter-form').submit()">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>

                    <div style="padding:6px 16px 4px;">
                        <a href="{{ route('dashboard') }}"
                           style="display:block;text-align:center;padding:9px;border-radius:8px;background:#f3f4f6;border:1px solid #e5e7eb;color:#6b7280;font-size:0.8rem;font-weight:600;text-decoration:none;transition:background 0.15s;"
                           onmouseover="this.style.background='#e5e7eb'"
                           onmouseout="this.style.background='#f3f4f6'">
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <div class="flex items-center gap-3 md:gap-12">
                    <button class="hamburger-btn" onclick="openDrawer()" aria-label="Menu">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="flex items-center gap-3">
                        <img src="{{ asset($logoAsset) }}" alt="FILKOM Space Logo" class="h-10 w-auto object-contain">
                        <span class="text-[#0A1628] text-xl font-bold tracking-tight">FILKOM Space</span>
                    </div>

                    <div class="hidden md:flex items-center gap-8 h-16">
                        <a href="/" class="h-full flex items-center {{ request()->is('/') ? 'text-[#0A1628] font-semibold border-b-2 border-[#D4AF37]' : 'text-gray-500 hover:text-[#0A1628] border-b-2 border-transparent' }}">
                            Home
                        </a>
                        <a href="/history" class="h-full flex items-center {{ request()->is('history') ? 'text-[#0A1628] font-semibold border-b-2 border-[#D4AF37]' : 'text-gray-500 hover:text-[#0A1628] border-b-2 border-transparent' }}">
                            My Bookings
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    @auth
                        <div class="relative">
                            <div id="notif-btn" class="cursor-pointer flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-50 transition-colors">
                                <img src="{{ asset($notifAsset) }}" alt="Notifications" class="w-10 h-10 object-contain">
                                @if ($unreadCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold w-4.5 h-4.5 flex items-center justify-center rounded-full border-2 border-white">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
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

                        <div class="relative flex items-center gap-3 border-l border-[#0A1628] pl-6">
                            <div id="profile-btn" class="cursor-pointer bg-cyan-200 rounded-full w-10 h-10 flex items-center justify-center hover:ring-2 hover:ring-cyan-300 hover:opacity-90 transition-all z-10">
                                <span class="text-sm font-bold text-cyan-800">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>

                            <div id="profile-dropdown" class="hidden absolute right-0 top-12 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden z-50">
                                <div class="flex items-center gap-4 px-4 py-4">
                                    <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-cyan-200 flex items-center justify-center">
                                        <span class="text-lg font-bold text-cyan-800">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="block font-semibold text-gray-700 truncate">{{ Auth::user()->name }}</span>
                                        <span class="block text-xs text-gray-400 mt-0.5 truncate">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 my-2"></div>
                                <div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-colors">
                                            <img src="{{ asset('assets/navbar/power.webp') }}" alt="Power Icon" class="w-4.5 h-4.5 object-contain">
                                            <span class="font-medium text-sm">Log Out</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <div class="flex items-center border-l border-gray-200 pl-6">
                            <a href="/login" class="px-5 py-2 text-sm font-semibold text-white bg-utama rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                                Log in
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    
    <main>
        {{ $slot }}
    </main>

    <script>
        // ── Drawer ──────────────────────────────
        function openDrawer() {
            document.getElementById('mobile-drawer').classList.add('open');
            document.body.style.overflow = 'hidden';
            syncDrawerFilters();
        }
        function closeDrawer() {
            document.getElementById('mobile-drawer').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.getElementById('drawer-overlay').addEventListener('click', closeDrawer);

        // Sync hidden filter inputs from the main desktop filter form values in URL
        function syncDrawerFilters() {
            const params = new URLSearchParams(window.location.search);
            const df = id => document.getElementById(id);
            if (df('df-date'))  df('df-date').value  = params.get('date')       || '';
            if (df('df-start')) df('df-start').value = params.get('start_time') || '';
            if (df('df-end'))   df('df-end').value   = params.get('end_time')   || '';
            if (df('df-room'))  df('df-room').value  = params.get('room_filter')|| 'all';
        }

        // Hide drawer filters section when not on dashboard
        document.addEventListener('DOMContentLoaded', function() {
            const isDashboard = window.location.pathname === '/' || window.location.pathname === '/dashboard';
            const filterSec = document.getElementById('drawer-filters-section');
            if (filterSec && !isDashboard) filterSec.style.display = 'none';
        });

        // ── Notif dropdown ──────────────────────
        const notifBtn  = document.getElementById('notif-btn');
        const notifDrop = document.getElementById('notif-dropdown');
        if (notifBtn && notifDrop) {
            notifBtn.addEventListener('click', e => { e.stopPropagation(); notifDrop.classList.toggle('hidden'); });
            document.addEventListener('click', () => notifDrop.classList.add('hidden'));
            notifDrop.addEventListener('click', e => e.stopPropagation());
        }

        const profileBtn  = document.getElementById('profile-btn');
        const profileDrop = document.getElementById('profile-dropdown');
        if (profileBtn && profileDrop) {
            profileBtn.addEventListener('click', e => { e.stopPropagation(); profileDrop.classList.toggle('hidden'); });
            document.addEventListener('click', () => profileDrop.classList.add('hidden'));
            profileDrop.addEventListener('click', e => e.stopPropagation());
        }
    </script>

</body>
</html>