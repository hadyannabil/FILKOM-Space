<x-layout.layout notificationCount="3">
    <section class="bg-hero-gradient py-16 px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Find Your Perfect Space</h1>
        <p class="text-gray-300 text-lg mb-10">Search and book rooms across FILKOM campus</p>

        <form class="max-w-5xl mx-auto bg-white rounded-xl shadow-2xl p-6 text-gray-800">
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
            
            <button type="submit" class="w-full mt-6 btn-gold-gradient text-[#0A1628]">
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
                        <input type="checkbox"> Gedung A
                    </label>
                    <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                        <input type="checkbox"> Gedung F
                    </label>
                    <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                        <input type="checkbox"> Gedung G
                    </label>
                    <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                        <input type="checkbox"> Gedung GKM
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
                    <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                        <input type="radio" name="capacity"> 51-100 people
                    </label>
                    <label class="flex items-center gap-2 mb-2 text-gray-600 cursor-pointer">
                        <input type="radio" name="capacity"> 101-200 people
                    </label>
                </div>

                <button class="w-full mt-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg">
                    Clear All Filters
                </button>
            </div>
        </aside>

        <div class="flex-1">
            <h2 class="text-2xl font-bold text-[#0A1628] mb-2">Available Rooms</h2>
            <p class="text-gray-500 mb-6">3 rooms available for your selected time</p>
            
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-dashboard.room-card 
                title="Room F3.1" 
                capacity="40" 
                image="ruang-kelas.webp" 
            />
            
            <x-dashboard.room-card 
                title="Algorithm Auditorium" 
                capacity="150" 
                image="auditorium-algoritma.webp" 
            />
            
            <x-dashboard.room-card 
                title="Room G1.3" 
                capacity="30" 
                image="lab.webp" 
            />
        </div>
        </div>

    </div>
</x-layout.layout>