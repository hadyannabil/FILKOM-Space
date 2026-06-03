@extends('admin.layout')

@section('title', 'Approvals')
@section('page-title', 'Approvals')
@section('page-subtitle', 'Manage all room reservation requests')

@section('content')
<div class="page-body">

    <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;" class="filter-row">
        @foreach(['', 'pending', 'approved', 'rejected', 'cancelled'] as $s)
        <button onclick="setStatusFilter('{{ $s }}')"
           id="tab-{{ $s ?: 'all' }}"
           style="padding:7px 18px;border-radius:20px;font-size:0.8rem;font-weight:600;text-decoration:none;cursor:pointer;
                  {{ request('status', '') === $s
                       ? 'background:#0A1628;color:#fff;border:1.5px solid #0A1628;'
                       : 'background:#fff;color:#6b7280;border:1.5px solid #e5e7eb;' }}">
            {{ $s ? ucfirst($s) : 'All' }}
        </button>
        @endforeach

        <div style="margin-left:auto;">
            <input type="text" id="search-approvals" placeholder="Search…"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:7px 14px;font-size:0.8rem;color:#374151;outline:none;width:200px;"
                   oninput="liveSearchApprovals(this.value)">
        </div>
    </div>

    <div class="stat-card" style="padding:0;overflow:hidden;">
        <div class="table-scroll-wrapper">
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
                <tr id="empty-row"><td colspan="7" style="text-align:center;padding:40px;color:#9baac4;">No reservations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div id="pagination-wrapper">
        @if($reservations->hasPages())
        <div style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8rem;color:#9baac4;">Showing {{ $reservations->firstItem() }} – {{ $reservations->lastItem() }} of {{ $reservations->total() }} results</span>
            <div style="display:flex;gap:6px;align-items:center;">
                {{-- Prev --}}
                @if($reservations->onFirstPage())
                    <span style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:default;">Prev</span>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Prev</a>
                @endif

                {{-- Page numbers --}}
                @php
                    $current   = $reservations->currentPage();
                    $last      = $reservations->lastPage();
                    $start     = max(1, $current - 2);
                    $end       = min($last, $current + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $reservations->url(1) }}" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">1</a>
                    @if($start > 2)
                        <span style="color:#9baac4;font-size:0.8rem;">…</span>
                    @endif
                @endif

                @for($p = $start; $p <= $end; $p++)
                    @if($p == $current)
                        <span style="padding:6px 10px;border:1px solid #0A1628;border-radius:7px;background:#0A1628;color:#fff;font-size:0.8rem;font-weight:600;">{{ $p }}</span>
                    @else
                        <a href="{{ $reservations->url($p) }}" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">{{ $p }}</a>
                    @endif
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)
                        <span style="color:#9baac4;font-size:0.8rem;">…</span>
                    @endif
                    <a href="{{ $reservations->url($last) }}" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">{{ $last }}</a>
                @endif

                {{-- Next --}}
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Next</a>
                @else
                    <span style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:default;">Next</span>
                @endif
            </div>
        </div>
        @endif
        </div>
    </div>
</div>

<script>
    const searchEndpoint = "{{ route('admin.approvals.search') }}";
    let   activeStatus   = new URLSearchParams(window.location.search).get('status') || '';
    let   searchTimer    = null;
    let   ajaxPage       = 1;
    let   lastQ          = '';

    // Isi search input dari URL param supaya tidak hilang setelah redirect
    const currentQ = new URLSearchParams(window.location.search).get('q') || '';
    if (currentQ) {
        document.getElementById('search-approvals').value = currentQ;
        lastQ = currentQ;
        fetchApprovals(currentQ, 1);
    }

    function setStatusFilter(status) {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        url.searchParams.delete('q');
        if (status === '') {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', status);
        }
        window.location.href = url.toString();
    }

    function liveSearchApprovals(q) {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            lastQ  = q.trim();
            ajaxPage = 1;
            if (lastQ === '') {
                // Kosongkan search → reload halaman normal
                const url = new URL(window.location.href);
                url.searchParams.delete('q');
                url.searchParams.delete('page');
                window.location.href = url.toString();
            } else {
                fetchApprovals(lastQ, 1);
            }
        }, 300);
    }

    async function fetchApprovals(q, page) {
        const tbody      = document.getElementById('approvals-tbody');
        const pagination = document.getElementById('pagination-wrapper');

        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:#9baac4;">Mencari...</td></tr>`;

        try {
            const params = new URLSearchParams({ q, status: activeStatus, page });
            const res    = await fetch(`${searchEndpoint}?${params}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) throw new Error('Request failed');
            const json = await res.json();

            if (json.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:#9baac4;">Tidak ada hasil untuk "<strong>${q}</strong>".</td></tr>`;
                pagination.innerHTML = '';
                return;
            }

            tbody.innerHTML = json.data.map(r => `
                <tr class="approval-row">
                    <td style="font-family:monospace;font-size:0.8rem;color:#6b7280;">${r.request_id}</td>
                    <td><div style="font-weight:600;color:#111827;">${r.event_name}</div></td>
                    <td>${r.pic_name}</td>
                    <td style="font-weight:500;">${r.room}</td>
                    <td>${r.date}</td>
                    <td>
                        <span class="${r.badge_class}" style="padding:3px 12px;border-radius:20px;font-size:0.75rem;font-weight:600;">
                            ${r.badge_label}
                        </span>
                    </td>
                    <td><a href="${r.detail_url}" class="review-btn">Detail</a></td>
                </tr>
            `).join('');

            // Render pagination AJAX
            renderAjaxPagination(json, q);

        } catch (err) {
            console.error('Search error:', err);
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:#ef4444;">Terjadi kesalahan. Coba lagi.</td></tr>`;
        }
    }

    function renderAjaxPagination(json, q) {
        const wrapper = document.getElementById('pagination-wrapper');
        if (json.last_page <= 1) { wrapper.innerHTML = ''; return; }

        const cur  = json.current_page;
        const last = json.last_page;
        const from = json.from ?? 0;
        const to   = json.to   ?? 0;

        const btn = (p, label, active = false, disabled = false) => {
            if (disabled) return `<span style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:default;">${label}</span>`;
            if (active)   return `<span style="padding:6px 10px;border:1px solid #0A1628;border-radius:7px;background:#0A1628;color:#fff;font-size:0.8rem;font-weight:600;">${label}</span>`;
            return `<button onclick="fetchApprovals('${q}', ${p})" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;cursor:pointer;">${label}</button>`;
        };

        let pages = '';
        const start = Math.max(1, cur - 2);
        const end   = Math.min(last, cur + 2);
        if (start > 1) { pages += btn(1, '1'); if (start > 2) pages += `<span style="color:#9baac4;font-size:0.8rem;">…</span>`; }
        for (let p = start; p <= end; p++) pages += btn(p, p, p === cur);
        if (end < last) { if (end < last - 1) pages += `<span style="color:#9baac4;font-size:0.8rem;">…</span>`; pages += btn(last, last); }

        wrapper.innerHTML = `
            <div style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.8rem;color:#9baac4;">Showing ${from}–${to} of ${json.total} results</span>
                <div style="display:flex;gap:6px;align-items:center;">
                    ${btn(cur - 1, 'Prev', false, cur === 1)}
                    ${pages}
                    ${btn(cur + 1, 'Next', false, cur === last)}
                </div>
            </div>`;
    }
</script>
@endsection