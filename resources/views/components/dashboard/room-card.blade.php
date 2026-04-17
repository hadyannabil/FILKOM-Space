@props(['title', 'capacity', 'image', 'slug', 'selectedDate', 'selectedTime'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
    
    <div class="h-48 overflow-hidden">
        <img src="{{ asset('assets/dashboard/' . $image) }}" 
             alt="{{ $title }}" 
             class="w-full h-full object-cover">
    </div>

    <div class="px-6 pt-4 pb-6 flex-1 flex flex-col">
        <h4 class="font-bold text-xl text-[#0A1628]">{{ $title }}</h4>

        <div class="mt-2 mb-4 text-gray-500 text-sm flex items-center gap-2">
            <img src="{{ asset('assets/dashboard/seats.webp') }}" alt="Capacity Icon" class="w-5 h-5 object-contain">
            <span>{{ $capacity }} Seats</span>
        </div>

        <a href="{{ route('reserve', ['room' => $slug, 'date' => $selectedDate, 'time' => $selectedTime]) }}" class="block text-center w-full mt-auto bg-[#0A1628] hover:bg-slate-800 text-white py-2 rounded-lg font-semibold transition duration-200">
            Reserve Room
        </a>
    </div>
</div>