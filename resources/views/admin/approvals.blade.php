@extends('admin.layout')

@section('title', 'Approvals')
@section('page-title', 'Approvals')
@section('page-subtitle', 'Manage all room reservation requests')

@section('content')
<div class="page-body">

    <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
        @foreach(['', 'pending', 'approved', 'rejected', 'cancelled'] as $s)
        <a href="{{ route('admin.approvals', $s ? ['status' => $s] : []) }}"
           style="padding:7px 18px;border-radius:20px;font-size:0.8rem;font-weight:600;text-decoration:none;
                  {{ request('status', '') === $s
                       ? 'background:#0A1628;color:#fff;border:1.5px solid #0A1628;'
                       : 'background:#fff;color:#6b7280;border:1.5px solid #e5e7eb;' }}">
            {{ $s ? ucfirst($s) : 'All' }}
        </a>
        @endforeach

        <div style="margin-left:auto;">
            <input type="text" id="search-approvals" placeholder="Search…"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:7px 14px;font-size:0.8rem;color:#374151;outline:none;width:200px;"
                   oninput="filterApprovals(this.value)">
        </div>
    </div>

    <div class="stat-card" style="padding:0;overflow:hidden;">
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Event Name</th>
                    <th>Applicant</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="approvals-tbody">
                @forelse($reservations as $r)
                @php
                    $statusMap = [
                        'pending'   => ['label'=>'Pending',   'class'=>'badge-pending'],
                        'approved'  => ['label'=>'Approved',  'class'=>'badge-approved'],
                        'rejected'  => ['label'=>'Rejected',  'class'=>'badge-rejected'],
                        'cancelled' => ['label'=>'Cancelled', 'class'=>'badge-cancelled'],
                    ];
                    $s = $statusMap[$r->status] ?? ['label'=>ucfirst($r->status),'class'=>'badge-cancelled'];
                @endphp
                <tr class="approval-row"
                    data-event="{{ strtolower($r->event_name) }}"
                    data-applicant="{{ strtolower($r->pic_name) }}"
                    data-id="{{ strtolower($r->request_id) }}">
                    <td style="font-family:monospace;font-size:0.8rem;color:#6b7280;">{{ $r->request_id }}</td>
                    <td>
                        <div style="font-weight:600;color:#111827;">{{ $r->event_name }}</div>
                        <div style="font-size:0.75rem;color:#9baac4;">{{ $r->event_type }}</div>
                    </td>
                    <td>{{ $r->pic_name }}</td>
                    <td style="font-weight:500;">{{ $r->room->name ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->reservation_date)->format('M j, Y') }}</td>
                    <td>
                        <span class="{{ $s['class'] }}" style="padding:3px 12px;border-radius:20px;font-size:0.75rem;font-weight:600;">
                            {{ $s['label'] }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.approval.detail', $r) }}" class="review-btn">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#9baac4;">No reservations found.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($reservations->hasPages())
        <div style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8rem;color:#9baac4;">Showing {{ $reservations->firstItem() }} – {{ $reservations->lastItem() }} of {{ $reservations->total() }} results</span>
            <div style="display:flex;gap:6px;">
                @if(!$reservations->onFirstPage())
                    <a href="{{ $reservations->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Previous</a>
                @endif
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#0A1628;color:#fff;font-size:0.8rem;text-decoration:none;">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function filterApprovals(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('#approvals-tbody .approval-row').forEach(row => {
        const matches = !q
            || row.dataset.event.includes(q)
            || row.dataset.applicant.includes(q)
            || row.dataset.id.includes(q);
        row.style.display = matches ? '' : 'none';
    });
}
</script>
@endsection
