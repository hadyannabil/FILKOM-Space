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
                    <option>09:00 AM</option>
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