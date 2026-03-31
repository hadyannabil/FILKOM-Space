@props([
    'logoAsset' => 'assets/navbar/logo.webp',
    'notifAsset' => 'assets/navbar/notif.webp', 
    'dropdownAsset' => 'assets/navbar/dropdown.webp',
    'notificationCount' => 3
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
                        <a href="/" class="text-[#0A1628] font-semibold border-b-2 border-[#D4AF37] h-full flex items-center">Home</a>
                        <a href="/history" class="text-gray-500 hover:text-[#0A1628] h-full flex items-center">My Bookings</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-50 transition-colors">
                        
                        <img src="{{ asset($notifAsset) }}" alt="Notifications" class="w-10 h-10 object-contain">
                        
                        @if ($notificationCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold w-4.5 h-4.5 flex items-center justify-center rounded-full border-2 border-white">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 border-l pl-6">
                        <img class="w-10 h-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" alt="Avatar">
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">Sarah Johnson</p>
                            <p class="text-xs text-gray-500">Student</p>
                        </div>
                        <img src="{{ asset($dropdownAsset) }}" alt="Dropdown Menu" class="w-6 h-6 object-contain opacity-70 hover:opacity-100 transition-opacity">
                    </div>
                </div>

            </div>
        </div>
    </nav>
    <main>
        {{ $slot }}
    </main>

</body>
</html>