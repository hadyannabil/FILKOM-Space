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
</head>
<body class="bg-gray-50 font-inter">

    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <div class="flex items-center gap-12">
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
                                        <span class="block text-xs text-gray-400 mt-0.5 truncate">{{ Auth::user()->prodi }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 my-2"></div>

                                <div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                            </svg>
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

</body>
</html>