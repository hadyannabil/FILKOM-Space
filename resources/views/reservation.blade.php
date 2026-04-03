<x-layout.layout>

    <div class="max-w-3xl mx-auto py-10 px-4">
        
        <div class="bg-white p-8 md:p-10 rounded-[20px] shadow-sm border border-gray-100">
            
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-yellow-100 p-2 rounded-lg text-yellow-600">
                        <img src="{{ asset('assets/reservation/room.webp') }}" alt="Room Icon" class="w-6 h-6 object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-[#0A1628]">Reserve {{ $roomName }}</h2>
                </div>
                <p class="text-gray-500 text-sm">Fill out the form below to submit your room reservation request.</p>
            </div>

            <form action="{{ route('reserve.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                    <input type="text" name="event_name" required placeholder="Enter event name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none placeholder-gray-400">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Person in Charge (PIC) Name</label>
                    <input type="text" name="pic_name" required placeholder="Enter PIC name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none placeholder-gray-400">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Attendees</label>
                    <input type="text" name="attendees" required placeholder="Enter attendees (e.g. 45 People)" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none placeholder-gray-400">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
                    <input type="text" name="notes" placeholder="Enter additional notes (e.g. require projector)" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none placeholder-gray-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Selected Date</label>
                        <input type="text" value="April 1, 2026" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 outline-none text-center">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Selected Time</label>
                        <input type="text" value="09:00 - 12:00" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 outline-none text-center">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Approval Letter (PDF)</label>
                    
                    <label for="file-upload" class="cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-center hover:bg-gray-50 transition duration-200">
                        
                        <div class="bg-gray-200 p-3 rounded-full mb-4">
                            <img src="{{ asset('assets/reservation/upload.webp') }}" alt="Upload Icon" class="w-6 h-6 object-contain">
                        </div>
                        
                        <p class="text-gray-700 font-medium mb-1">Drag and drop your PDF file here</p>
                        <p class="text-gray-400 text-sm mb-6">or click to browse files</p>
                        
                        <input type="file" name="approval_letter" accept=".pdf" required class="hidden" id="file-upload">
                        
                        <div class="inline-flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium text-gray-600">
                            <img src="{{ asset('assets/reservation/pdf.webp') }}" alt="PDF Icon" class="w-6 h-6 object-contain">
                            <span>PDF files only, max 10MB</span>
                        </div>
                    </label>
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <a href="/" class="flex-1 py-3 border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 text-center transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 py-3 bg-[#0A1628] text-white rounded-xl font-semibold hover:bg-slate-800 transition duration-200">
                        Submit Reservation
                    </button>
                </div>

            </form>

        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Your reservation request will be reviewed within 1 hours. You will receive an email confirmation once approved.
        </p>

    </div>

</x-layout.layout>