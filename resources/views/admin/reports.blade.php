@extends('admin.layout')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Analytics and statistics for FILKOM Space room usage.')

@section('content')
<style>
    /* Tab navigation */
    .report-tabs { display: flex; gap: 6px; background: #fff; border-radius: 12px; padding: 6px; border: 1px solid #eaecf5; width: fit-content; }
    .report-tab  { padding: 8px 20px; border-radius: 8px; font-size: 0.825rem; font-weight: 600; color: #9baac4; cursor: pointer; border: none; background: none; transition: all 0.18s; }
    .report-tab.active { background: #0A1628; color: #fff; }
    .report-tab:hover:not(.active) { background: #f4f6fb; color: #374151; }

    /* Metric cards */
    .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .metric-card { background: #fff; border-radius: 14px; padding: 22px; border: 1px solid #eaecf5; }
    .metric-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
    .metric-value { font-size: 2rem; font-weight: 800; color: #0A1628; line-height: 1; }
    .metric-label { font-size: 0.78rem; color: #9baac4; font-weight: 500; margin-top: 4px; }
    .metric-change { font-size: 0.75rem; font-weight: 600; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
    .change-up   { color: #16a34a; }
    .change-down { color: #dc2626; }

    /* Chart card */
    .chart-card { background: #fff; border-radius: 14px; border: 1px solid #eaecf5; overflow: hidden; }
    .chart-header { padding: 20px 24px 16px; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
    .chart-title { font-size: 0.95rem; font-weight: 700; color: #0A1628; margin: 0; }
    .chart-body { padding: 24px; }

    /* Bar chart */
    .bar-group { display: flex; align-items: flex-end; gap: 3px; }
    .bar { border-radius: 4px 4px 0 0; transition: opacity 0.15s; cursor: default; }
    .bar:hover { opacity: 0.8; }
    .chart-labels { display: flex; gap: 3px; margin-top: 8px; }
    .chart-label { font-size: 0.68rem; color: #9baac4; text-align: center; flex: 1; }

    /* Donut chart */
    .donut-wrapper { display: flex; align-items: center; gap: 32px; }
    .legend-item { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-label { font-size: 0.8rem; color: #374151; }
    .legend-value { font-size: 0.8rem; color: #9baac4; margin-left: auto; padding-left: 16px; }

    /* Room usage table */
    .room-bar-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f8f9fc; }
    .room-bar-row:last-child { border-bottom: none; }
    .room-bar-track { flex: 1; height: 8px; background: #f0f1f5; border-radius: 99px; overflow: hidden; }
    .room-bar-fill  { height: 100%; border-radius: 99px; transition: width 1s cubic-bezier(.4,0,.2,1); }

    /* Export buttons */
    .btn-export { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 9px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: 1.5px solid #e5e7eb; background: #fff; color: #374151; transition: all 0.18s; text-decoration: none; }
    .btn-export:hover { background: #f4f6fb; border-color: #d1d5db; }
    .btn-export.primary { background: #0A1628; border-color: #0A1628; color: #fff; }
    .btn-export.primary:hover { background: #0f2040; }

    /* Period filter */
    .period-select { border: 1px solid #e5e7eb; border-radius: 8px; padding: 7px 12px; font-size: 0.8rem; color: #374151; background: #fff; cursor: pointer; outline: none; }

    /* Top events table */
    .rank-badge { width: 24px; height: 24px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; }

    /* Trend arrow */
    .trend-up   { color: #16a34a; }
    .trend-down { color: #dc2626; }
    .trend-flat { color: #9baac4; }

    @media (max-width: 900px) {
        .metric-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="page-body">

    {{-- Period & Export Bar --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div class="report-tabs" id="period-tabs">
            <button class="report-tab active" data-period="weekly"  onclick="setPeriod('weekly',  this)">Weekly</button>
            <button class="report-tab"        data-period="monthly" onclick="setPeriod('monthly', this)">Monthly</button>
            <button class="report-tab"        data-period="yearly"  onclick="setPeriod('yearly',  this)">Yearly</button>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <select class="period-select" id="range-select" onchange="onRangeChange(this.value)">
                {{-- options filled by JS --}}
            </select>
            <button class="btn-export" onclick="exportCSV()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17"/></svg>
                Export CSV
            </button>
            <button class="btn-export primary" onclick="printReport()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print Report
            </button>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="metric-grid" style="margin-bottom:20px;" id="metric-grid">
        {{-- filled by JS --}}
    </div>

    {{-- Charts Row --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;margin-bottom:20px;">

        {{-- Bar Chart: Reservation Trend --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Tren Reservasi</h3>
                <div style="display:flex;gap:12px;align-items:center;">
                    <span style="display:flex;align-items:center;gap:5px;font-size:0.75rem;color:#374151;"><span style="width:10px;height:10px;background:#0A1628;border-radius:3px;display:inline-block;"></span>Disetujui</span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:0.75rem;color:#374151;"><span style="width:10px;height:10px;background:#ef4444;border-radius:3px;display:inline-block;"></span>Ditolak</span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:0.75rem;color:#374151;"><span style="width:10px;height:10px;background:#D4AF37;border-radius:3px;display:inline-block;"></span>Pending</span>
                </div>
            </div>
            <div class="chart-body">
                <div id="bar-chart" style="height:180px;display:flex;align-items:flex-end;gap:6px;"></div>
                <div id="bar-labels" class="chart-labels" style="margin-top:8px;"></div>
            </div>
        </div>

        {{-- Donut Chart: Status Distribution --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Status Reservasi</h3>
            </div>
            <div class="chart-body">
                <div class="donut-wrapper">
                    <svg id="donut-svg" width="120" height="120" viewBox="0 0 120 120" style="flex-shrink:0;transform:rotate(-90deg);">
                        <circle cx="60" cy="60" r="44" fill="none" stroke="#f0f1f5" stroke-width="20"/>
                        {{-- arcs filled by JS --}}
                    </svg>
                    <div id="donut-legend" style="flex:1;"></div>
                </div>
                <div id="donut-total" style="text-align:center;margin-top:12px;font-size:0.8rem;color:#9baac4;"></div>
            </div>
        </div>
    </div>

    {{-- Room Usage + Top Events Row --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px;">

        {{-- Room Usage --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Penggunaan Ruangan</h3>
                <span style="font-size:0.75rem;color:#9baac4;" id="room-subtitle">Total reservasi per ruangan</span>
            </div>
            <div class="chart-body" id="room-usage-body">
                {{-- filled by JS --}}
            </div>
        </div>

        {{-- Top Events --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Top Event Types</h3>
                <span style="font-size:0.75rem;color:#9baac4;">Berdasarkan frekuensi</span>
            </div>
            <div class="chart-body" id="top-events-body">
                {{-- filled by JS --}}
            </div>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="chart-card" style="margin-bottom:20px;">
        <div class="chart-header">
            <h3 class="chart-title" id="detail-table-title">Detail Reservasi Minggu Ini</h3>
            <input type="text" id="table-search" placeholder="Cari event atau pemohon…"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:7px 14px;font-size:0.8rem;color:#374151;outline:none;width:220px;"
                   oninput="filterDetailTable(this.value)">
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Pemohon</th>
                        <th>Ruangan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="text-align:right;">Peserta</th>
                    </tr>
                </thead>
                <tbody id="detail-table-body">
                    @forelse($reservations as $r)
                    <tr class="detail-row"
                        data-event="{{ strtolower($r->event_name) }}"
                        data-pic="{{ strtolower($r->pic_name) }}">
                        <td>
                            <div style="font-weight:600;color:#111827;">{{ $r->event_name }}</div>
                            <div style="font-size:0.75rem;color:#9baac4;">{{ $r->event_type }}</div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="avatar" style="width:28px;height:28px;font-size:0.7rem;">{{ substr($r->pic_name, 0, 1) }}</div>
                                <span style="font-size:0.875rem;">{{ $r->pic_name }}</span>
                            </div>
                        </td>
                        <td style="font-weight:500;font-size:0.875rem;">{{ $r->room->name ?? '—' }}</td>
                        <td>
                            <div style="font-size:0.875rem;font-weight:500;">{{ \Carbon\Carbon::parse($r->reservation_date)->format('d M Y') }}</div>
                            <div style="font-size:0.73rem;color:#9baac4;">{{ substr($r->start_time,0,5) }}–{{ substr($r->end_time,0,5) }}</div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($r->status) {
                                    'approved'  => 'badge-approved',
                                    'rejected'  => 'badge-rejected',
                                    'cancelled' => 'badge-cancelled',
                                    default     => 'badge-pending',
                                };
                                $badgeLabel = match($r->status) {
                                    'approved'  => 'Disetujui',
                                    'rejected'  => 'Ditolak',
                                    'cancelled' => 'Dibatalkan',
                                    default     => 'Pending',
                                };
                            @endphp
                            <span class="{{ $badgeClass }}" style="padding:3px 10px;border-radius:6px;font-size:0.73rem;font-weight:600;">
                                {{ $badgeLabel }}
                            </span>
                        </td>
                        <td style="text-align:right;font-weight:600;color:#374151;">{{ $r->attendees }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#9baac4;">Tidak ada data reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservations->hasPages())
        <div style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8rem;color:#9baac4;">Showing {{ $reservations->firstItem() }} – {{ $reservations->lastItem() }} of {{ $reservations->total() }}</span>
            <div style="display:flex;gap:6px;">
                @if($reservations->onFirstPage())
                    <button disabled style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:not-allowed;">Prev</button>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Prev</a>
                @endif
                @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $pg => $url)
                    <a href="{{ $url }}" style="padding:6px 12px;border:1px solid {{ $pg == $reservations->currentPage() ? '#0A1628' : '#e5e7eb' }};border-radius:7px;background:{{ $pg == $reservations->currentPage() ? '#0A1628' : '#fff' }};color:{{ $pg == $reservations->currentPage() ? '#fff' : '#374151' }};font-size:0.8rem;text-decoration:none;">{{ $pg }}</a>
                @endforeach
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#fff;color:#374151;font-size:0.8rem;text-decoration:none;">Next</a>
                @else
                    <button disabled style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb;color:#d1d5db;font-size:0.8rem;cursor:not-allowed;">Next</button>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

<script>
// ─── DATA LAYER (from PHP) ────────────────────────────────────────────────
const RAW = @json($chartData);

// ─── STATE ───────────────────────────────────────────────────────────────
let currentPeriod = 'weekly';

// ─── PERIOD TABS ────────────────────────────────────────────────────────
function setPeriod(period, btn) {
    currentPeriod = period;
    document.querySelectorAll('.report-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    populateRangeSelect(period);
    renderAll();
}

function populateRangeSelect(period) {
    const sel = document.getElementById('range-select');
    sel.innerHTML = '';
    if (period === 'weekly') {
        ['Minggu Ini', 'Minggu Lalu', '2 Minggu Lalu', '3 Minggu Lalu'].forEach((label, i) => {
            const o = document.createElement('option');
            o.value = i; o.textContent = label; sel.appendChild(o);
        });
    } else if (period === 'monthly') {
        const months = ['Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'];
        const now = new Date();
        for (let i = 0; i < 6; i++) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const o = document.createElement('option');
            o.value = i;
            o.textContent = months[d.getMonth()] + ' ' + d.getFullYear();
            sel.appendChild(o);
        }
    } else {
        const year = new Date().getFullYear();
        [year, year-1, year-2].forEach((y, i) => {
            const o = document.createElement('option');
            o.value = i; o.textContent = 'Tahun ' + y; sel.appendChild(o);
        });
    }
}

function onRangeChange(val) { renderAll(); }

// ─── METRIC CARDS ────────────────────────────────────────────────────────
const METRICS_CONF = [
    { key: 'total',     label: 'Total Reservasi', icon: '#3b82f6', bg: '#eef9ff',
      svgPath: '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>' },
    { key: 'approved',  label: 'Disetujui',       icon: '#16a34a', bg: '#f0fdf4',
      svgPath: '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>' },
    { key: 'rejected',  label: 'Ditolak',         icon: '#ef4444', bg: '#fef2f2',
      svgPath: '<circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>' },
    { key: 'cancelled', label: 'Dibatalkan',      icon: '#6b7280', bg: '#f3f4f6',
      svgPath: '<circle cx="12" cy="12" r="10"/><path d="M8 12h8"/>' },
];

function renderMetrics(data) {
    const grid = document.getElementById('metric-grid');
    grid.innerHTML = METRICS_CONF.map(m => {
        const val  = data.metrics[m.key] ?? 0;
        const prev = data.metrics_prev?.[m.key] ?? 0;
        const diff = val - prev;
        const pct  = prev > 0 ? Math.round((diff / prev) * 100) : 0;
        const updown = diff >= 0 ? 'change-up' : 'change-down';
        const arrow  = diff >= 0 ? '↑' : '↓';
        const changeHtml = prev > 0
            ? `<div class="metric-change ${updown}">${arrow} ${Math.abs(pct)}% vs periode lalu</div>`
            : `<div class="metric-change" style="color:#9baac4;">Periode pertama</div>`;
        return `
        <div class="metric-card">
            <div class="metric-icon" style="background:${m.bg};">
                <svg fill="none" stroke="${m.icon}" stroke-width="2" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    ${m.svgPath}
                </svg>
            </div>
            <div class="metric-value" data-target="${val}">0</div>
            <div class="metric-label">${m.label}</div>
            ${changeHtml}
        </div>`;
    }).join('');
    
    grid.querySelectorAll('.metric-value').forEach(el => {
        const target = parseInt(el.dataset.target, 10);
        if (isNaN(target)) return;
        let cur = 0;
        const step = Math.max(1, Math.ceil(target / 25));
        const t = setInterval(() => {
            cur = Math.min(cur + step, target);
            el.textContent = cur.toLocaleString('id-ID');
            if (cur >= target) clearInterval(t);
        }, 28);
    });
}

function renderBarChart(data) {
    const chart  = document.getElementById('bar-chart');
    const labels = document.getElementById('bar-labels');
    const bars   = data.trend ?? [];
    if (!bars.length) { chart.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;">Tidak ada data.</p>'; return; }

    const maxVal = Math.max(...bars.map(b => (b.approved||0) + (b.rejected||0) + (b.pending||0)), 1);
    const H = 180;

    chart.innerHTML = bars.map(b => {
        const totalH = H - 20;
        const ap = Math.round(((b.approved||0) / maxVal) * totalH);
        const rj = Math.round(((b.rejected||0) / maxVal) * totalH);
        const pe = Math.round(((b.pending ||0) / maxVal) * totalH);
        return `
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;justify-content:flex-end;height:${H}px;">
            <div class="bar" style="width:100%;height:${pe}px;background:#D4AF37;" title="Pending: ${b.pending||0}"></div>
            <div class="bar" style="width:100%;height:${rj}px;background:#ef4444;" title="Ditolak: ${b.rejected||0}"></div>
            <div class="bar" style="width:100%;height:${ap}px;background:#0A1628;" title="Disetujui: ${b.approved||0}"></div>
        </div>`;
    }).join('');

    labels.innerHTML = bars.map(b => `<div class="chart-label">${b.label}</div>`).join('');
}

function renderDonut(data) {
    const svg    = document.getElementById('donut-svg');
    const legend = document.getElementById('donut-legend');
    const total  = document.getElementById('donut-total');
    const dist   = data.status_dist ?? {};

    const COLORS = { approved:'#0A1628', pending:'#D4AF37', rejected:'#ef4444', cancelled:'#6b7280' };
    const LABELS = { approved:'Disetujui', pending:'Pending', rejected:'Ditolak', cancelled:'Dibatalkan' };
    const keys   = Object.keys(COLORS);
    const sum    = keys.reduce((s, k) => s + (dist[k]||0), 0);

    if (sum === 0) {
        svg.querySelector('circle').setAttribute('stroke', '#f0f1f5');
        legend.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;">Belum ada data.</p>';
        return;
    }

    svg.querySelectorAll('.donut-arc').forEach(e => e.remove());

    const R = 44; const C = 2 * Math.PI * R;
    let offset = 0;
    const arcs = [];
    keys.forEach(k => {
        const val = dist[k] || 0;
        if (!val) return;
        const pct  = val / sum;
        const dash = C * pct;
        const gap  = C - dash;
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', 60); circle.setAttribute('cy', 60); circle.setAttribute('r', R);
        circle.setAttribute('fill', 'none');
        circle.setAttribute('stroke', COLORS[k]);
        circle.setAttribute('stroke-width', 20);
        circle.setAttribute('stroke-dasharray', `${dash} ${gap}`);
        circle.setAttribute('stroke-dashoffset', -offset);
        circle.classList.add('donut-arc');
        svg.appendChild(circle);
        offset += dash;
        arcs.push({ k, val, pct });
    });

    legend.innerHTML = arcs.map(({ k, val, pct }) => `
        <div class="legend-item">
            <div class="legend-dot" style="background:${COLORS[k]};"></div>
            <span class="legend-label">${LABELS[k]}</span>
            <span class="legend-value">${val} (${Math.round(pct*100)}%)</span>
        </div>`).join('');

    total.textContent = `Total: ${sum} reservasi`;
}

function renderRoomUsage(data) {
    const body = document.getElementById('room-usage-body');
    const rooms = data.room_usage ?? [];
    if (!rooms.length) { body.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;">Tidak ada data.</p>'; return; }

    const maxCount = Math.max(...rooms.map(r => r.count), 1);
    const COLORS   = ['#0A1628','#1e3a6b','#2d5aa0','#3b7fd4','#6baed6'];

    body.innerHTML = rooms.map((r, i) => {
        const pct = Math.round((r.count / maxCount) * 100);
        return `
        <div class="room-bar-row">
            <div style="width:110px;font-size:0.78rem;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${r.name}">${r.name}</div>
            <div class="room-bar-track">
                <div class="room-bar-fill" style="width:${pct}%;background:${COLORS[i % COLORS.length]};"></div>
            </div>
            <div style="width:32px;text-align:right;font-size:0.78rem;font-weight:700;color:#0A1628;">${r.count}</div>
        </div>`;
    }).join('');
}

function renderTopEvents(data) {
    const body  = document.getElementById('top-events-body');
    const types = data.event_types ?? [];
    if (!types.length) { body.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;">Tidak ada data.</p>'; return; }

    const RANK_COLORS = ['#D4AF37','#9baac4','#cd7f32'];
    body.innerHTML = types.map((t, i) => `
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:${i < types.length-1 ? '1px solid #f8f9fc' : 'none'};">
            <div class="rank-badge" style="background:${RANK_COLORS[i] || '#f0f1f5'};color:${i < 3 ? '#fff' : '#6b7280'};">${i+1}</div>
            <div style="flex:1;">
                <div style="font-size:0.85rem;font-weight:600;color:#111827;">${t.type}</div>
                <div style="font-size:0.73rem;color:#9baac4;">${t.count} reservasi</div>
            </div>
            <div style="font-size:0.8rem;font-weight:700;color:#0A1628;">${t.pct}%</div>
        </div>`).join('');
}

function renderAll() {
    const rangeIdx = parseInt(document.getElementById('range-select').value || 0, 10);
    const data = getDataForPeriod(currentPeriod, rangeIdx);

    renderMetrics(data);
    renderBarChart(data);
    renderDonut(data);
    renderRoomUsage(data);
    renderTopEvents(data);

    document.getElementById('detail-table-title').textContent =
        currentPeriod === 'weekly'  ? 'Detail Reservasi – ' + data.label :
        currentPeriod === 'monthly' ? 'Detail Reservasi – ' + data.label :
        'Detail Reservasi – ' + data.label;
}

function getDataForPeriod(period, rangeIdx) {
    const now = new Date();

    function isoDate(d) { return d.toISOString().split('T')[0]; }

    function dateRange() {
        if (period === 'weekly') {
            const monday = new Date(now);
            monday.setDate(now.getDate() - now.getDay() + 1 - rangeIdx * 7);
            monday.setHours(0,0,0,0);
            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            return { start: isoDate(monday), end: isoDate(sunday) };
        }
        if (period === 'monthly') {
            const d = new Date(now.getFullYear(), now.getMonth() - rangeIdx, 1);
            const end = new Date(d.getFullYear(), d.getMonth() + 1, 0);
            return { start: isoDate(d), end: isoDate(end) };
        }
        const year = now.getFullYear() - rangeIdx;
        return { start: `${year}-01-01`, end: `${year}-12-31` };
    }

    const range = dateRange();
    const all   = RAW.filter(r => r.date >= range.start && r.date <= range.end);

    const approved  = all.filter(r => r.status === 'approved').length;
    const rejected  = all.filter(r => r.status === 'rejected').length;
    const pending   = all.filter(r => r.status === 'pending').length;
    const cancelled = all.filter(r => r.status === 'cancelled').length;

    const prevRange = (() => {
        if (period === 'weekly') {
            const pStart = new Date(range.start); pStart.setDate(pStart.getDate() - 7);
            const pEnd   = new Date(range.end);   pEnd.setDate(pEnd.getDate() - 7);
            return { start: isoDate(pStart), end: isoDate(pEnd) };
        }
        if (period === 'monthly') {
            const d = new Date(now.getFullYear(), now.getMonth() - rangeIdx - 1, 1);
            const e = new Date(d.getFullYear(), d.getMonth() + 1, 0);
            return { start: isoDate(d), end: isoDate(e) };
        }
        const y = now.getFullYear() - rangeIdx - 1;
        return { start: `${y}-01-01`, end: `${y}-12-31` };
    })();
    const prev = RAW.filter(r => r.date >= prevRange.start && r.date <= prevRange.end);

    const trend = buildTrend(all, period, range);

    const roomMap = {};
    all.forEach(r => { roomMap[r.room] = (roomMap[r.room]||0) + 1; });
    const room_usage = Object.entries(roomMap)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 6)
        .map(([name, count]) => ({ name, count }));

    const typeMap = {};
    all.forEach(r => { typeMap[r.event_type] = (typeMap[r.event_type]||0) + 1; });
    const total = all.length || 1;
    const event_types = Object.entries(typeMap)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5)
        .map(([type, count]) => ({ type, count, pct: Math.round((count/total)*100) }));

    const label = buildLabel(period, rangeIdx, range);

    return {
        label,
        metrics: { total: all.length, approved, rejected, pending, cancelled },
        metrics_prev: {
            total:     prev.length,
            approved:  prev.filter(r => r.status === 'approved').length,
            rejected:  prev.filter(r => r.status === 'rejected').length,
            pending:   prev.filter(r => r.status === 'pending').length,
            cancelled: prev.filter(r => r.status === 'cancelled').length,
        },
        status_dist: { approved, pending, rejected, cancelled },
        trend,
        room_usage,
        event_types,
    };
}

function buildTrend(items, period, range) {
    if (period === 'weekly') {
        const days  = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
        const start = new Date(range.start);
        return days.map((label, i) => {
            const d = new Date(start); d.setDate(start.getDate() + i);
            const ds = d.toISOString().split('T')[0];
            const day = items.filter(r => r.date === ds);
            return {
                label,
                approved:  day.filter(r => r.status === 'approved').length,
                rejected:  day.filter(r => r.status === 'rejected').length,
                pending:   day.filter(r => r.status === 'pending').length,
            };
        });
    }
    if (period === 'monthly') {
        const start = new Date(range.start);
        const daysInMonth = new Date(start.getFullYear(), start.getMonth()+1, 0).getDate();
        const weeks = Math.ceil(daysInMonth / 7);
        return Array.from({ length: weeks }, (_, i) => {
            const wStart = new Date(start); wStart.setDate(1 + i*7);
            const wEnd   = new Date(start); wEnd.setDate(Math.min(daysInMonth, (i+1)*7));
            const ws = wStart.toISOString().split('T')[0];
            const we = wEnd.toISOString().split('T')[0];
            const week = items.filter(r => r.date >= ws && r.date <= we);
            return {
                label: `W${i+1}`,
                approved:  week.filter(r => r.status === 'approved').length,
                rejected:  week.filter(r => r.status === 'rejected').length,
                pending:   week.filter(r => r.status === 'pending').length,
            };
        });
    }
    
    const MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    const year   = parseInt(range.start.split('-')[0]);
    return MONTHS.map((label, i) => {
        const m = String(i+1).padStart(2,'0');
        const month = items.filter(r => r.date.startsWith(`${year}-${m}`));
        return {
            label,
            approved:  month.filter(r => r.status === 'approved').length,
            rejected:  month.filter(r => r.status === 'rejected').length,
            pending:   month.filter(r => r.status === 'pending').length,
        };
    });
}

function buildLabel(period, rangeIdx, range) {
    if (period === 'weekly') {
        const labels = ['Minggu Ini','Minggu Lalu','2 Minggu Lalu','3 Minggu Lalu'];
        return labels[rangeIdx] ?? range.start;
    }
    if (period === 'monthly') {
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        const d = new Date(); d.setMonth(d.getMonth() - rangeIdx);
        return months[d.getMonth()] + ' ' + d.getFullYear();
    }
    return 'Tahun ' + (new Date().getFullYear() - rangeIdx);
}

function filterDetailTable(q) {
    q = q.toLowerCase().trim();
    let vis = 0;
    document.querySelectorAll('#detail-table-body .detail-row').forEach(row => {
        const match = !q || row.dataset.event.includes(q) || row.dataset.pic.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) vis++;
    });
    let emp = document.getElementById('detail-empty');
    if (!vis && !emp) {
        emp = document.createElement('tr');
        emp.id = 'detail-empty';
        emp.innerHTML = `<td colspan="6" style="text-align:center;padding:40px;color:#9baac4;">Tidak ada hasil untuk "<strong>${q}</strong>".</td>`;
        document.getElementById('detail-table-body').appendChild(emp);
    } else if (vis && emp) { emp.remove(); }
}

function exportCSV() {
    const rangeIdx = parseInt(document.getElementById('range-select').value||0, 10);
    const data = getDataForPeriod(currentPeriod, rangeIdx);
    const range = (() => {
        const now = new Date();
        function isoDate(d) { return d.toISOString().split('T')[0]; }
        if (currentPeriod === 'weekly') {
            const monday = new Date(now); monday.setDate(now.getDate() - now.getDay() + 1 - rangeIdx * 7); monday.setHours(0,0,0,0);
            const sunday = new Date(monday); sunday.setDate(monday.getDate() + 6);
            return { start: isoDate(monday), end: isoDate(sunday) };
        }
        if (currentPeriod === 'monthly') {
            const d = new Date(now.getFullYear(), now.getMonth() - rangeIdx, 1);
            const end = new Date(d.getFullYear(), d.getMonth() + 1, 0);
            return { start: isoDate(d), end: isoDate(end) };
        }
        const year = now.getFullYear() - rangeIdx;
        return { start: `${year}-01-01`, end: `${year}-12-31` };
    })();

    const items = RAW.filter(r => r.date >= range.start && r.date <= range.end);
    const header = ['Event','Tipe','Pemohon','Ruangan','Tanggal','Jam Mulai','Jam Selesai','Peserta','Status'];
    const rows = items.map(r => [
        `"${r.event_name}"`, r.event_type, `"${r.pic_name}"`, `"${r.room}"`,
        r.date, r.start_time, r.end_time, r.attendees, r.status
    ].join(','));
    const csv  = [header.join(','), ...rows].join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = `report_${data.label.replace(/\s+/g,'-')}.csv`; a.click();
    URL.revokeObjectURL(url);
}

function printReport() { window.print(); }

document.addEventListener('DOMContentLoaded', () => {
    populateRangeSelect('weekly');
    renderAll();
});
</script>

<style>
@media print {
    .admin-sidebar, .topbar, .report-tabs, #range-select, .btn-export, #table-search { display: none !important; }
    .admin-content { margin-left: 0 !important; }
    .chart-card { break-inside: avoid; }
}
</style>
@endsection
