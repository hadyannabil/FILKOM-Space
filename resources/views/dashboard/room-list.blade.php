<div class="flex-1">
    <h2 class="text-2xl font-bold text-[#0A1628] mb-2">Available Rooms</h2>
    <p class="text-gray-500 mb-6">24 rooms available for your selected time</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @include('dashboard.room-card', ['title' => 'Room F1.1', 'capacity' => '40'])
        @include('dashboard.room-card', ['title' => 'Algorithm Auditorium', 'capacity' => '150'])
        @include('dashboard.room-card', ['title' => 'Room G2.3', 'capacity' => '30'])
    </div>
</div>