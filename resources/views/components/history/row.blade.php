@props(['eventName', 'roomName', 'date', 'time', 'duration', 'status'])

@php
    // Logika untuk menentukan warna otomatis berdasarkan status
    $statusTheme = match($status) {
        'Approved' => 'bg-green-50 text-green-700 border-green-100',
        'Pending'  => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'Rejected' => 'bg-red-50 text-red-700 border-red-100',
        default    => 'bg-gray-50 text-gray-700 border-gray-100',
    };
@endphp

<tr class="hover:bg-gray-50 transition-colors">
    <td class="px-6 py-4 font-semibold text-[#0A1628]">{{ $eventName }}</td>
    <td class="px-6 py-4">{{ $roomName }}</td>
    <td class="px-6 py-4">
        <span class="block text-gray-800">{{ $date }}</span>
        <span class="block text-xs text-gray-500 mt-1">{{ $time }}</span>
    </td>
    <td class="px-6 py-4">{{ $duration }}</td>
    <td class="px-6 py-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border {{ $statusTheme }}">
            
            @if($status === 'Approved')
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            @elseif($status === 'Pending')
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif($status === 'Rejected')
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif

            {{ $status }}
        </span>
    </td>
</tr>