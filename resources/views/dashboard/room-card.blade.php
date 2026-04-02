<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
    
    <div class="h-48 bg-slate-200 flex items-center justify-center text-slate-400">
        Gambar Ruangan
    </div>

    <div class="p-6 flex-1 flex flex-col">
        <h4 class="font-bold text-xl text-[#0A1628]">{{ $title }}</h4>

        <div class="mt-2 mb-4 text-gray-500 text-sm">
            👥 {{ $capacity }} Seats
        </div>

        <button class="w-full mt-auto bg-[#0A1628] hover:bg-slate-800 text-white py-2 rounded-lg font-semibold">
            Reserve Room
        </button>
    </div>
</div>