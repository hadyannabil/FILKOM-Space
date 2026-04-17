@props(['id', 'eventName', 'roomName', 'date', 'time', 'duration', 'status'])

@php
    $statusTheme = match($status) {
        'Approved'  => 'bg-green-50 text-green-700 border-green-100',
        'Pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'Completed' => 'bg-blue-50 text-blue-700 border-blue-100',
        'Rejected'  => 'bg-red-50 text-red-700 border-red-100',
        'Cancelled' => 'bg-gray-50 text-gray-500 border-gray-200',
        default     => 'bg-gray-50 text-gray-700 border-gray-100',
    };
@endphp

<tr class="hover:bg-gray-50 transition-colors" id="row-{{ $id }}">
    <td class="px-6 py-4 font-semibold text-[#0A1628]">{{ $eventName }}</td>
    <td class="px-6 py-4">{{ $roomName }}</td>
    <td class="px-6 py-4">
        <span class="block text-gray-800">{{ $date }}</span>
        <span class="block text-xs text-gray-500 mt-1">{{ $time }}</span>
    </td>
    <td class="px-6 py-4">{{ $duration }}</td>
    <td class="px-6 py-4">
        <span id="status-badge-{{ $id }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusTheme }}">
            <span id="status-text-{{ $id }}">{{ $status }}</span>
        </span>
    </td>
    <td class="px-6 py-4">
        @if($status === 'Approved' || $status === 'Pending')
            <button 
                id="btn-cancel-{{ $id }}"
                onclick="openCancelModal('{{ $id }}', '{{ $eventName }}', '{{ $roomName }}')"
                class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-lg hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all focus:outline-none focus:ring-2 focus:ring-red-200">
                Batalkan
            </button>
        @endif
    </td>
</tr>