@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', "Welcome back! Here's what's happening today.")

@section('content')
<div class="page-body">

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:26px;">

        <div class="stat-card" style="position:relative;overflow:hidden;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#9baac4;font-weight:500;margin:0 0 6px;">Pending Approvals</p>
                    <h2 style="font-size:2.2rem;font-weight:800;color:#0A1628;margin:0;" id="stat-pending">{{ $pendingCount }}</h2>
                    <p style="font-size:0.78rem;color:#e67e22;font-weight:500;margin:6px 0 0;"></p>
                </div>
                <div style="width:48px;height:48px;background:#fff5f5;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="width:24px;height:24px;">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#9baac4;font-weight:500;margin:0 0 6px;">Today's Events</p>
                    <h2 style="font-size:2.2rem;font-weight:800;color:#0A1628;margin:0;" id="stat-today">{{ $todayEvents }}</h2>
                    <p style="font-size:0.78rem;color:#16a34a;font-weight:500;margin:6px 0 0;"></p>
                </div>
                <div style="width:48px;height:48px;background:#eef9ff;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24" style="width:24px;height:24px;">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#9baac4;font-weight:500;margin:0 0 6px;">Total Rooms</p>
                    <h2 style="font-size:2.2rem;font-weight:800;color:#0A1628;margin:0;" id="stat-rooms">{{ $totalRooms }}</h2>
                    <p style="font-size:0.78rem;color:#6b7280;font-weight:500;margin:6px 0 0;"></p>
                </div>
                <div style="width:48px;height:48px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="width:24px;height:24px;">
                        <path d="M3 9h18M9 21V9m6 12V9M3 3h18v18H3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card" style="padding:0;overflow:hidden;">
        <div style="padding:20px 24px 16px;border-bottom:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:1rem;font-weight:700;color:#0A1628;margin:0;">Recent Pending Requests</h3>
            {{-- Live filter with JS DOM --}}
            <input type="text" id="search-input" placeholder="Search event or applicant…"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:7px 14px;font-size:0.8rem;color:#374151;outline:none;width:220px;"
                   oninput="filterTable(this.value)">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Applicant Name</th>
                    <th>Room</th>
                    <th>Date &amp; Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
                @forelse($requests as $r)
                <tr class="table-row"
                    data-event="{{ strtolower($r->event_name) }}"
                    data-applicant="{{ strtolower($r->pic_name) }}">
                    <td>
                        <div style="font-weight:600;color:#111827;">{{ $r->event_name }}</div>
                        <div style="font-size:0.75rem;color:#9baac4;">{{ $r->event_type }}</div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div class="avatar" style="width:30px;height:30px;font-size:0.75rem;">{{ substr($r->pic_name,0,1) }}</div>
                            <span>{{ $r->pic_name }}</span>
                        </div>
                    </td>
                    <td style="font-weight:500;">{{ $r->room->name ?? '—' }}</td>
                    <td>
                        <div style="font-weight:500;">{{ \Carbon\Carbon::parse($r->reservation_date)->format('F j, Y') }}</div>
                        <div style="font-size:0.75rem;color:#9baac4;">{{ substr($r->start_time,0,5) }} – {{ substr($r->end_time,0,5) }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.approval.detail', $r) }}" class="review-btn">Review</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#9baac4;">No pending requests.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($requests->hasPages())
        <div style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8rem;color:#9baac4;">Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} results</span>
            <div style="display:flex;gap:6px;">
                @if($requests->onFirstPage())
                    <button disabled style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:not-allowed;">Previous</button>
                @else
                    <a href="{{ $requests->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Previous</a>
                @endif
                @foreach($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                    <a href="{{ $url }}" style="padding:6px 12px;border:1px solid {{ $page == $requests->currentPage() ? '#0A1628' : '#e5e7eb' }};border-radius:7px;background:{{ $page == $requests->currentPage() ? '#0A1628' : '#fff' }};color:{{ $page == $requests->currentPage() ? '#fff' : '#374151' }};font-size:0.8rem;text-decoration:none;">{{ $page }}</a>
                @endforeach
                @if($requests->hasMorePages())
                    <a href="{{ $requests->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Next</a>
                @else
                    <button disabled style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:not-allowed;">Next</button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function filterTable(query) {
    const q    = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#requests-table-body .table-row');
    let   visible = 0;

    rows.forEach(row => {
        const event     = row.dataset.event     || '';
        const applicant = row.dataset.applicant || '';
        const matches   = q === '' || event.includes(q) || applicant.includes(q);
        row.style.display = matches ? '' : 'none';
        if (matches) visible++;
    });

    let emptyRow = document.getElementById('empty-row');
    if (visible === 0 && !emptyRow) {
        emptyRow = document.createElement('tr');
        emptyRow.id = 'empty-row';
        emptyRow.innerHTML = `<td colspan="5" style="text-align:center;padding:40px;color:#9baac4;">No results for "<strong>${query}</strong>".</td>`;
        document.getElementById('requests-table-body').appendChild(emptyRow);
    } else if (visible > 0 && emptyRow) {
        emptyRow.remove();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="stat-"]').forEach(el => {
        const target = parseInt(el.textContent, 10);
        if (isNaN(target)) return;
        let current = 0;
        const step  = Math.ceil(target / 30);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 30);
    });
});
</script>
@endsection
