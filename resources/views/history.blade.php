<x-layout.layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#0A1628] mb-2">My Booking History</h1>
            <p class="text-gray-500">View and manage all your room bookings</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">5</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-lg bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Pending</p>
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">2</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-lg bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Approved</p>
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">2</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Rejected</p>
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">1</h3>
                </div>
            </div>

        </div>

        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    
                    <thead class="border-b border-gray-100 text-gray-700 font-semibold bg-white">
                        <tr>
                            <th class="px-6 py-5">Event Name</th>
                            <th class="px-6 py-5">Room Name</th>
                            <th class="px-6 py-5">Date & Time</th>
                            <th class="px-6 py-5">Duration</th>
                            <th class="px-6 py-5">Status</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        
                        <x-history.row 
                            eventName="Artificial Intelligence" 
                            roomName="F3.3" 
                            date="Apr 5, 2026" 
                            time="2:00 PM - 4:00 PM" 
                            duration="2 hours" 
                            status="Approved" 
                        />

                        <x-history.row 
                            eventName="Database" 
                            roomName="F4.2" 
                            date="Apr 8, 2026" 
                            time="10:00 AM - 12:00 PM" 
                            duration="2 hours" 
                            status="Pending" 
                        />

                        <x-history.row 
                            eventName="User Experience Design" 
                            roomName="F2.5" 
                            date="Apr 2, 2026" 
                            time="9:00 AM - 11:00 AM" 
                            duration="2 hours" 
                            status="Rejected" 
                        />

                        <x-history.row 
                            eventName="Mobile Development" 
                            roomName="Lab G1.3" 
                            date="Apr 6, 2026" 
                            time="1:00 PM - 4:00 PM" 
                            duration="3 hours" 
                            status="Approved" 
                        />

                        <x-history.row 
                            eventName="Cybersecurity" 
                            roomName="F4.10" 
                            date="Apr 7, 2026" 
                            time="3:00 PM - 5:00 PM" 
                            duration="2 hours" 
                            status="Pending" 
                        />

                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
                <p class="text-sm text-gray-500">Showing 1 to 5 of 24 results</p>
                <div class="flex items-center gap-1.5">
                    <button class="px-3.5 py-1.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">Previous</button>
                    <button class="px-3.5 py-1.5 border border-transparent bg-[#0A1628] text-white rounded-lg text-sm font-medium">1</button>
                    <button class="px-3.5 py-1.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">2</button>
                    <button class="px-3.5 py-1.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">3</button>
                    <button class="px-3.5 py-1.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">Next</button>
                </div>
            </div>

        </div>
    </div>
</x-layout.layout>