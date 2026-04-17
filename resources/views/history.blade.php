<x-layout.layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#0A1628] mb-2">My Booking History</h1>
            <p class="text-gray-500">View and manage all your room bookings</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">{{ $total ?? 0 }}</h3>
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
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">{{ $pending }}</h3>
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
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">{{ $approved }}</h3>
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
                    <h3 class="text-2xl font-bold text-[#0A1628] leading-none">{{ $rejected }}</h3>
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
                            <th class="px-6 py-5">Action</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-100 text-gray-700">

                        @forelse($reservations as $rsv)
                            @php
                                $statusTheme = match($rsv['status']) {
                                    'Approved'  => 'bg-green-50 text-green-700 border-green-100',
                                    'Pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'Completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Rejected'  => 'bg-red-50 text-red-700 border-red-100',
                                    'Cancelled' => 'bg-gray-50 text-gray-500 border-gray-200',
                                    default     => 'bg-gray-50 text-gray-700 border-gray-100',
                                };

                                $durationText = '-'; 
                                if (isset($rsv['time']) && str_contains($rsv['time'], '-')) {
                                    $timeParts = explode('-', $rsv['time']);
                                    if (count($timeParts) === 2) {
                                        $start = \Carbon\Carbon::parse(trim($timeParts[0]));
                                        $end = \Carbon\Carbon::parse(trim($timeParts[1]));
                                        $diffInMinutes = $start->diffInMinutes($end);
                                        
                                        $hours = floor($diffInMinutes / 60);
                                        $minutes = $diffInMinutes % 60;
                                        
                                        $durationArray = [];
                                        if ($hours > 0) {
                                            $durationArray[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
                                        }
                                        if ($minutes > 0) {
                                            $durationArray[] = $minutes . ' min';
                                        }
                                        
                                        $durationText = implode(' ', $durationArray);
                                    }
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $rsv['id'] }}">
                                <td class="px-6 py-4 font-semibold text-[#0A1628]">{{ $rsv['event_name'] }}</td>
                                <td class="px-6 py-4">{{ $rsv['room_name'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="block text-gray-800">{{ $rsv['date'] }}</span>
                                    <span class="block text-xs text-gray-500 mt-1">{{ $rsv['time'] }}</span>
                                </td>
                                
                                <td class="px-6 py-4">{{ $durationText }}</td>
                                
                                <td class="px-6 py-4">
                                    <span id="status-badge-{{ $rsv['id'] }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusTheme }}">
                                        <span id="status-text-{{ $rsv['id'] }}">{{ $rsv['status'] }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if(in_array($rsv['status'], ['Approved', 'Pending']))
                                        <button type="button"
                                            id="btn-cancel-{{ $rsv['id'] }}"
                                            onclick="openCancelModal('{{ $rsv['id'] }}', '{{ addslashes($rsv['event_name']) }}', '{{ addslashes($rsv['room_name']) }}')"
                                            class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-lg transition-all hover:border-gray-300 focus:text-red-600 focus:bg-red-50 focus:border-red-200 focus:ring-2 focus:ring-red-200 active:text-red-600 active:bg-red-50 active:border-red-200 outline-none">
                                            Batalkan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada reservasi. <a href="/" class="text-[#0A1628] font-semibold hover:underline">Buat reservasi sekarang</a>.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                @if(count($reservations) > 0)
                    <p class="text-sm text-gray-500">Showing 1 to {{ count($reservations) }} of {{ count($reservations) }} results</p>
                @else
                    <p class="text-sm text-gray-500">Showing 0 results</p>
                @endif
            </div>

        </div>
    </div>

    <div id="cancelModal" class="fixed inset-0 z-[100] hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center transition-opacity opacity-0">
        <div id="modalContent" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform scale-95 transition-all duration-300">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-[#0A1628] mb-2">Batalkan Reservasi?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Apakah Anda yakin ingin membatalkan reservasi untuk acara <strong id="modalEventName" class="text-gray-800"></strong> di ruang <strong id="modalRoomName" class="text-gray-800"></strong>? Tindakan ini tidak dapat diurungkan.
            </p>
            
            <div class="flex items-center gap-3 w-full">
                <button onclick="closeCancelModal()" class="w-full px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                    Kembali
                </button>
                <button onclick="confirmCancel()" class="w-full px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentCancelId = null;

        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('modalContent');
        const eventNameSpan = document.getElementById('modalEventName');
        const roomNameSpan = document.getElementById('modalRoomName');

        function openCancelModal(id, eventName, roomName) {
            currentCancelId = id;
            eventNameSpan.textContent = eventName;
            roomNameSpan.textContent = roomName;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeCancelModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentCancelId = null;
            }, 300);
        }

        async function confirmCancel() {
            if(!currentCancelId) return;

            try {
                const response = await fetch(`/reserve/${currentCancelId}/cancel`, {
                    method: 'POST', 
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH' 
                    })
                });

                const result = await response.json();

                if(response.ok) {
                    const statusBadge = document.getElementById('status-badge-' + currentCancelId);
                    const statusText = document.getElementById('status-text-' + currentCancelId);
                    const cancelButton = document.getElementById('btn-cancel-' + currentCancelId);

                    if(statusBadge && statusText) {
                        statusText.textContent = 'Cancelled';
                        statusBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border bg-gray-50 text-gray-500 border-gray-200';
                    }
                    
                    if(cancelButton) {
                        cancelButton.remove();
                    }

                    closeCancelModal();

                } else {
                    alert('Gagal membatalkan reservasi: ' + result.message);
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
</x-layout.layout>