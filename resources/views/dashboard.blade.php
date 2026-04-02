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
                        <img src="{{ asset('assets/navbar/logo.webp') }}" alt="FILKOM Space Logo" class="h-10 w-auto object-contain">
                        <span class="text-[#0A1628] text-xl font-bold tracking-tight">FILKOM Space</span>
                    </div>
                    <div class="hidden md:flex items-center gap-8 h-16">
                        <a href="/" class="text-[#0A1628] font-semibold border-b-2 border-[#D4AF37] h-full flex items-center">Home</a>
                        <a href="/history" class="text-gray-500 hover:text-[#0A1628] h-full flex items-center">My Bookings</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('assets/navbar/notif.webp') }}" alt="Notifications" class="w-10 h-10 object-contain">
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold w-4.5 h-4.5 flex items-center justify-center rounded-full border-2 border-white">
                            3
                        </span>
                    </div>

                    <div class="flex items-center gap-3 border-l pl-6">
                        <img class="w-10 h-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" alt="Avatar">
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">Sarah Johnson</p>
                            <p class="text-xs text-gray-500">Student</p>
                        </div>
                        <img src="{{ asset('assets/navbar/dropdown.webp') }}" alt="Dropdown Menu" class="w-6 h-6 object-contain opacity-70 hover:opacity-100 transition-opacity">
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section class="bg-[#1C2C44] py-16 px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Find Your Perfect Space</h1>
            <p class="text-gray-300 text-lg mb-10">Search and book rooms across FILKOM campus</p>

            <form onsubmit="alert('woi')" class="max-w-5xl mx-auto bg-white rounded-xl shadow-2xl p-6 text-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date</label>
                        <input type="date" class="w-full border-gray-200 rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] px-4 py-2 border">
                    </div>
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Start Time</label>
                        <select class="w-full border-gray-200 rounded-lg px-4 py-2 border">
                            <option>08:00 AM</option>
                        </select>
                    </div>
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-600 mb-1">End Time</label>
                        <select class="w-full border-gray-200 rounded-lg px-4 py-2 border">
                            <option>09:00 AM</option>
                        </select>
                    </div>
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Rooms</label>
                        <select class="w-full border-gray-200 rounded-lg px-4 py-2 border">
                            <option>All rooms</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="w-full mt-6 bg-[#F3C240] hover:bg-[#D4AF37] text-[#0A1628] font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <img src="{{ asset('assets/dashboard/search.webp') }}" alt="Search Icon" class="w-5.5 h-5.5 object-contain"> 
                    Search Rooms
                </button>
            </form>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-1/4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 text-[#0A1628]">Filters</h3>
                    
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Building Location</h4>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="checkbox"> Building A
                        </label>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="checkbox"> Building F
                        </label>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="checkbox"> Building G
                        </label>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="checkbox"> Building GKM
                        </label>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Capacity Range</h4>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="radio" name="capacity"> 1-20 people
                        </label>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                            <input type="radio" name="capacity"> 21-50 people
                        </label>
                    </div>

                    <button class="w-full mt-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg">
                        Clear All Filters
                    </button>
                </div>
            </aside>

            <div class="flex-1">
                <h2 class="text-2xl font-bold text-[#0A1628] mb-2">Available Rooms</h2>
                <p class="text-gray-500 mb-6">24 rooms available for your selected time</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                <x-dashboard.room-card title="Room F1.1" capacity="40" />
                <x-dashboard.room-card title="Algorithm Auditorium" capacity="150" />
                <x-dashboard.room-card title="Room G2.3" capacity="30" />

                </div>
            </div>

        </div>
    </main>

</body>
</html>