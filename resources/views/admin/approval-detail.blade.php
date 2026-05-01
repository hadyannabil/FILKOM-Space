@extends('admin.layout')

@section('title', 'Approval Detail')
@section('page-title', 'Approval Detail')
@section('page-subtitle', 'Review and process reservation request')

@section('content')
<div class="page-body">

    <a href="{{ route('admin.approvals') }}"
       style="display:inline-flex;align-items:center;gap:6px;color:#6b7280;font-size:0.875rem;text-decoration:none;margin-bottom:22px;font-weight:500;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#6b7280'">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back to Approvals
    </a>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:22px;">

        <div class="stat-card">
            <h3 style="font-size:1.05rem;font-weight:700;color:#0A1628;margin:0 0 4px;">Reservation Details</h3>
            <p style="font-size:0.8rem;color:#9baac4;margin:0 0 22px;">Request ID: {{ $reservation->request_id }}</p>

            <div style="margin-bottom:18px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Event Name</label>
                <p style="font-size:1.05rem;font-weight:700;color:#111827;margin:4px 0 0;">{{ $reservation->event_name }}</p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Person in Charge</label>
                <p style="font-size:0.95rem;font-weight:700;color:#111827;margin:4px 0 0;">{{ $reservation->pic_name }}</p>
                <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0;">{{ $reservation->pic_email ?? $reservation->user->email }}</p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Room Selected</label>
                <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                    <div style="width:34px;height:34px;background:#eef2ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <svg fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M3 9h18M9 21V9m6 12V9M3 3h18v18H3z"/></svg>
                    </div>
                    <div>
                        <p style="font-size:0.9rem;font-weight:700;color:#111827;margin:0;">{{ $reservation->room->name ?? '—' }}</p>
                        <p style="font-size:0.75rem;color:#6b7280;margin:0;">{{ $reservation->room->floor ?? '' }}{{ $reservation->room->building ? ', '.$reservation->room->building : '' }}</p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Date &amp; Time</label>
                <p style="font-size:0.95rem;font-weight:700;color:#111827;margin:6px 0 4px;">
                    📅 {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('F j, Y') }}
                </p>
                <p style="font-size:0.9rem;color:#374151;margin:0;">
                    🕐 {{ substr($reservation->start_time,0,5) }} – {{ substr($reservation->end_time,0,5) }}
                </p>
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Attendees</label>
                <p style="font-size:0.95rem;font-weight:700;color:#111827;margin:4px 0 0;">{{ $reservation->attendees }} People</p>
            </div>

            @if($reservation->notes)
            <div style="margin-bottom:22px;">
                <label style="font-size:0.75rem;font-weight:600;color:#9baac4;text-transform:uppercase;letter-spacing:0.05em;">Additional Notes</label>
                <p style="font-size:0.875rem;color:#374151;margin:6px 0 0;line-height:1.6;">{{ $reservation->notes }}</p>
            </div>
            @endif

            <div style="display:flex;align-items:center;gap:10px;padding-top:14px;border-top:1px solid #f0f1f5;">
                <span style="font-size:0.8rem;color:#6b7280;font-weight:500;">Status</span>
                @php
                    $statusMap = [
                        'pending'   => ['label'=>'Pending Approval',  'class'=>'badge-pending'],
                        'approved'  => ['label'=>'Approved',          'class'=>'badge-approved'],
                        'rejected'  => ['label'=>'Rejected',          'class'=>'badge-rejected'],
                        'cancelled' => ['label'=>'Cancelled',         'class'=>'badge-cancelled'],
                    ];
                    $s = $statusMap[$reservation->status] ?? ['label'=>ucfirst($reservation->status),'class'=>'badge-cancelled'];
                @endphp
                <span class="{{ $s['class'] }}" style="padding:4px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                    ⏳ {{ $s['label'] }}
                </span>
            </div>

            @if($reservation->reviewed_at)
            <p style="font-size:0.75rem;color:#9baac4;margin:8px 0 0;">
                Reviewed by {{ $reservation->reviewer->name ?? 'Admin' }} on {{ $reservation->reviewed_at->format('F j, Y H:i') }}
            </p>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;gap:18px;">

            <div class="stat-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <h3 style="font-size:1rem;font-weight:700;color:#0A1628;margin:0;">Supporting Document</h3>
                    @if($reservation->approval_letter)
                    <a href="{{ Storage::url($reservation->approval_letter) }}" download
                       style="display:inline-flex;align-items:center;gap:6px;background:#f8f9fc;border:1px solid #e5e7eb;border-radius:8px;padding:7px 14px;font-size:0.78rem;font-weight:600;color:#374151;text-decoration:none;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                        Download Letter
                    </a>
                    @endif
                </div>

                @if($reservation->approval_letter)
                <div style="border:2px dashed #e5e7eb;border-radius:12px;padding:30px;text-align:center;">
                    <div style="width:64px;height:64px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg viewBox="0 0 24 24" style="width:32px;height:32px;" fill="none">
                            <rect x="4" y="2" width="12" height="18" rx="2" fill="#ef4444" opacity="0.15"/>
                            <rect x="4" y="2" width="12" height="18" rx="2" stroke="#ef4444" stroke-width="1.5"/>
                            <path d="M8 7h8M8 11h5" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/>
                            <text x="5" y="17" font-size="4" fill="#ef4444" font-weight="700">PDF</text>
                        </svg>
                    </div>
                    <p style="font-size:0.9rem;font-weight:700;color:#111827;margin:0 0 4px;">Request Letter.pdf</p>
                    <p style="font-size:0.78rem;color:#9baac4;margin:0 0 16px;">Official request document submitted by the requester</p>
                    <div style="display:flex;gap:10px;justify-content:center;">
                        <a href="{{ Storage::url($reservation->approval_letter) }}" target="_blank"
                           style="background:#0A1628;color:#fff;padding:9px 20px;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                            👁 View Document
                        </a>
                        <a href="{{ Storage::url($reservation->approval_letter) }}" target="_blank"
                           style="background:#fff;border:1.5px solid #e5e7eb;color:#374151;padding:9px 20px;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;">
                            ↗ Open in New Tab
                        </a>
                    </div>
                </div>
                @else
                <div style="border:2px dashed #e5e7eb;border-radius:12px;padding:30px;text-align:center;color:#9baac4;font-size:0.875rem;">
                    No supporting document uploaded.
                </div>
                @endif
            </div>

            @if($reservation->status === 'pending')
            <div class="stat-card">
                <h3 style="font-size:0.95rem;font-weight:700;color:#0A1628;margin:0 0 14px;">Take Action</h3>

                {{-- Rejection reason (shown/hidden by JS DOM) --}}
                <div style="margin-bottom:14px;">
                    <label style="font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px;display:block;">
                        Rejection Reason <span style="color:#9baac4;font-weight:400;">(Optional)</span>
                    </label>
                    <textarea id="rejection-reason-input"
                              rows="3"
                              placeholder="Provide a reason if rejecting this request…"
                              style="width:100%;border:1.5px solid #e5e7eb;border-radius:9px;padding:10px 14px;font-size:0.85rem;color:#374151;resize:vertical;outline:none;font-family:inherit;box-sizing:border-box;"
                              onfocus="this.style.borderColor='#0A1628'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <form id="reject-form" method="POST" action="{{ route('admin.approval.reject', $reservation) }}" style="flex:1;">
                        @csrf
                        <input type="hidden" name="rejection_reason" id="rejection-reason-hidden">
                        <button type="button" class="btn-reject" style="width:100%;justify-content:center;" onclick="submitReject()">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                            Reject Request
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.approval.approve', $reservation) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-approve" style="width:100%;justify-content:center;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;"><path d="M20 6L9 17l-5-5"/></svg>
                            Approve Request
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($reservation->status === 'rejected' && $reservation->rejection_reason)
            <div class="stat-card" style="border-left:4px solid #dc2626;">
                <p style="font-size:0.78rem;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 6px;">Rejection Reason</p>
                <p style="font-size:0.875rem;color:#374151;margin:0;">{{ $reservation->rejection_reason }}</p>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
function submitReject() {
    const reason = document.getElementById('rejection-reason-input').value.trim();
    document.getElementById('rejection-reason-hidden').value = reason;

    if (!reason) {
        const confirmed = confirm('Yakin ingin menolak tanpa alasan?');
        if (!confirmed) return;
    }

    document.getElementById('reject-form').submit();
}
</script>
@endsection
