@extends('admin.layout')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Analytics and statistics for FILKOM Space room usage.')

@section('content')
<style>
    .report-tabs { display: flex; gap: 6px; background: #fff; border-radius: 12px; padding: 6px; border: 1px solid #eaecf5; width: fit-content; }
    .report-tab  { padding: 8px 20px; border-radius: 8px; font-size: 0.825rem; font-weight: 600; color: #9baac4; cursor: pointer; border: none; background: none; transition: all 0.18s; }
    .report-tab.active { background: #0A1628; color: #fff; }
    .report-tab:hover:not(.active) { background: #f4f6fb; color: #374151; }

    .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .metric-card { background: #fff; border-radius: 14px; padding: 22px; border: 1px solid #eaecf5; }
    .metric-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
    .metric-value { font-size: 2rem; font-weight: 800; color: #0A1628; line-height: 1; }
    .metric-label { font-size: 0.78rem; color: #9baac4; font-weight: 500; margin-top: 4px; }

    .chart-card { background: #fff; border-radius: 14px; border: 1px solid #eaecf5; overflow: hidden; }
    .chart-header { padding: 20px 24px 16px; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
    .chart-title { font-size: 0.95rem; font-weight: 700; color: #0A1628; margin: 0; }
    .chart-body { padding: 24px; }

    .bar-group { display: flex; align-items: flex-end; gap: 3px; }
    .bar { border-radius: 4px 4px 0 0; transition: opacity 0.15s; cursor: default; }
    .bar:hover { opacity: 0.8; }
    .chart-labels { display: flex; gap: 3px; margin-top: 8px; }
    .chart-label { font-size: 0.68rem; color: #9baac4; text-align: center; flex: 1; }

    .donut-wrapper { display: flex; align-items: center; gap: 32px; }
    .legend-item { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-label { font-size: 0.8rem; color: #374151; }
    .legend-value { font-size: 0.8rem; color: #9baac4; margin-left: auto; padding-left: 16px; }

    .room-bar-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f8f9fc; }
    .room-bar-row:last-child { border-bottom: none; }
    .room-bar-track { flex: 1; height: 8px; background: #f0f1f5; border-radius: 99px; overflow: hidden; }
    .room-bar-fill  { height: 100%; border-radius: 99px; transition: width 1s cubic-bezier(.4,0,.2,1); }

    .btn-export { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 9px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: 1.5px solid #e5e7eb; background: #fff; color: #374151; transition: all 0.18s; text-decoration: none; }
    .btn-export:hover { background: #f4f6fb; border-color: #d1d5db; }
    .btn-export.primary { background: #0A1628; border-color: #0A1628; color: #fff; }
    .btn-export.primary:hover { background: #0f2040; }

    .period-select { border: 1px solid #e5e7eb; border-radius: 8px; padding: 7px 12px; font-size: 0.8rem; color: #374151; background: #fff; cursor: pointer; outline: none; }

    .rank-badge { width: 24px; height: 24px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; }

    .trend-up   { color: #16a34a; }
    .trend-down { color: #dc2626; }
    .trend-flat { color: #9baac4; }

    @media (max-width: 900px) {
        .metric-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .metric-grid { grid-template-columns: repeat(2, 1fr); }
        .report-tabs { width: 100%; justify-content: space-between; }
        .report-tab { flex: 1; text-align: center; }
        .charts-row { grid-template-columns: 1fr !important; }
        .period-export-row { flex-direction: column; align-items: stretch !important; }
        .period-export-row > div { width: 100%; }
    }
</style>

<div class="page-body">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;" class="period-export-row">
        <div class="report-tabs" id="period-tabs">
            <button class="report-tab active" data-period="weekly"  onclick="setPeriod('weekly',  this)">Weekly</button>
            <button class="report-tab"        data-period="monthly" onclick="setPeriod('monthly', this)">Monthly</button>
            <button class="report-tab"        data-period="yearly"  onclick="setPeriod('yearly',  this)">Yearly</button>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <select class="period-select" id="range-select" onchange="onRangeChange(this.value)">
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

    <div class="metric-grid" style="margin-bottom:20px;" id="metric-grid">
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;margin-bottom:20px;" class="charts-row">

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

        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Status Reservasi</h3>
            </div>
            <div class="chart-body">
                <div class="donut-wrapper">
                    <svg id="donut-svg" width="120" height="120" viewBox="0 0 120 120" style="flex-shrink:0;transform:rotate(-90deg);">
                        <circle cx="60" cy="60" r="44" fill="none" stroke="#f0f1f5" stroke-width="20"/>
                    </svg>
                    <div id="donut-legend" style="flex:1;"></div>
                </div>
                <div id="donut-total" style="text-align:center;margin-top:12px;font-size:0.8rem;color:#9baac4;"></div>
            </div>
        </div>
    </div>

    <div style="margin-bottom:20px;">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Top Rooms</h3>
                <span style="font-size:0.75rem;color:#9baac4;">Ruangan paling sering dipesan</span>
            </div>
            <div id="top-rooms-body" style="padding:0 24px 16px;">
            </div>
        </div>
    </div>

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
                </tbody>
            </table>
        </div>
        <div id="detail-pagination" style="padding:16px 24px;border-top:1px solid #f0f1f5;display:flex;align-items:center;justify-content:space-between;"></div>
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
    document.getElementById('range-select').value = '0';
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

        return `
        <div class="metric-card">
            <div class="metric-icon" style="background:${m.bg};">
                <svg fill="none" stroke="${m.icon}" stroke-width="2" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    ${m.svgPath}
                </svg>
            </div>
            <div class="metric-value" data-target="${val}">0</div>
            <div class="metric-label">${m.label}</div>
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
        svg.querySelectorAll('.donut-arc').forEach(e => e.remove());
        svg.querySelector('circle').setAttribute('stroke', '#f0f1f5');
        legend.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;">Belum ada data.</p>';
        total.textContent = '';
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

function renderTopRooms(data) {
    const body  = document.getElementById('top-rooms-body');
    const rooms = data.room_usage ?? [];
    if (!rooms.length) { body.innerHTML = '<p style="color:#9baac4;font-size:0.8rem;padding:16px 0;">Tidak ada data.</p>'; return; }

    const RANK_COLORS = ['#D4AF37', '#9baac4', '#cd7f32'];
    const max = rooms[0].count || 1;
    const items = rooms.slice(0, 6).map((r, i) => `
        <div style="display:flex;align-items:center;gap:14px;padding:12px 16px;background:#f9fafb;border-radius:10px;">
            <div class="rank-badge" style="background:${RANK_COLORS[i] || '#e5e7eb'};color:${i < 3 ? '#fff' : '#6b7280'};">${i+1}</div>
            <div style="flex:1;">
                <div style="font-size:0.875rem;font-weight:600;color:#111827;margin-bottom:5px;">${r.name}</div>
                <div style="background:#e5e7eb;border-radius:99px;height:7px;overflow:hidden;">
                    <div style="background:#0A1628;height:7px;border-radius:99px;width:${Math.round((r.count/max)*100)}%;transition:width 0.5s;"></div>
                </div>
            </div>
            <div style="font-size:0.9rem;font-weight:700;color:#0A1628;min-width:32px;text-align:right;">${r.count} <span style="font-size:0.7rem;color:#9baac4;font-weight:400;">reservasi</span></div>
        </div>`).join('');

    body.innerHTML = `<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding-top:8px;">${items}</div>`;
}

function renderRoomUsage(data) {
    const body = document.getElementById('room-usage-body');
    if (!body) return;
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
    if (!body) return;  // elemen tidak ada di halaman ini
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
    const rangeIdx = Math.max(0, parseInt(document.getElementById('range-select').value ?? '0', 10) || 0);
    const data = getDataForPeriod(currentPeriod, rangeIdx);

    renderMetrics(data);
    renderBarChart(data);
    renderDonut(data);
    renderTopRooms(data);
    renderTopEvents(data);

    // Judul tabel: "Detail Reservasi Bulanan – Juni 2026" dll
    const periodLabel = currentPeriod === 'weekly'  ? 'Mingguan' :
                        currentPeriod === 'monthly' ? 'Bulanan'  : 'Tahunan';
    document.getElementById('detail-table-title').textContent =
        `Detail Reservasi ${periodLabel} – ${data.label}`;

    // Reset search & render tabel pakai rows yang sudah difilter dari getDataForPeriod
    const searchEl = document.getElementById('table-search');
    if (searchEl) searchEl.value = '';
    renderDetailTable(data.rows);
}

function getDataForPeriod(period, rangeIdx) {
    const now = new Date();

    // BUGFIX: toISOString() converts to UTC — in UTC+7, local midnight becomes previous day UTC.
    // Always use local date parts to avoid off-by-one timezone errors.
    function isoDate(d) {
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }

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

    const label = buildLabel(period, rangeIdx, range);

    return {
        label,
        range,
        rows: all,
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
        // BUGFIX: use local date parts to avoid timezone shift in toISOString()
        function localDate(d) {
            return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        }
        return Array.from({ length: weeks }, (_, i) => {
            const wStart = new Date(start); wStart.setDate(1 + i*7);
            const wEnd   = new Date(start); wEnd.setDate(Math.min(daysInMonth, 1 + i*7 + 6));
            const ws = localDate(wStart);
            const we = localDate(wEnd);
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

// ─── DETAIL TABLE (client-side, period-aware, search across all pages) ───────
const DETAIL_PAGE_SIZE = 10;
let detailCurrentPage = 1;
let detailFilteredRows = [];

function filterDetailTable(q) {
    // Reset ke halaman 1 setiap kali search berubah
    detailCurrentPage = 1;
    renderDetailTable();
}

function renderDetailTable(rows) {
    const q = (document.getElementById('table-search')?.value || '').toLowerCase().trim();

    // Kalau rows diberikan (dari renderAll), simpan sebagai dataset aktif
    if (rows !== undefined) {
        detailFilteredRows = rows;
        detailCurrentPage = 1;
    }

    // Filter berdasarkan search query — mencakup SEMUA data, bukan hanya halaman ini
    const filtered = q
        ? detailFilteredRows.filter(r =>
            r.event_name.toLowerCase().includes(q) ||
            r.pic_name.toLowerCase().includes(q))
        : detailFilteredRows;

    const total     = filtered.length;
    const totalPages = Math.max(1, Math.ceil(total / DETAIL_PAGE_SIZE));
    detailCurrentPage = Math.min(detailCurrentPage, totalPages);

    const start  = (detailCurrentPage - 1) * DETAIL_PAGE_SIZE;
    const paged  = filtered.slice(start, start + DETAIL_PAGE_SIZE);

    const STATUS_BADGE = {
        approved:  { cls: 'badge-approved',  label: 'Disetujui'  },
        rejected:  { cls: 'badge-rejected',  label: 'Ditolak'    },
        cancelled: { cls: 'badge-cancelled', label: 'Dibatalkan' },
        pending:   { cls: 'badge-pending',   label: 'Pending'    },
    };

    const MONTHS_ID = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    function fmtDate(ds) {
        const d = new Date(ds + 'T00:00:00');
        return `${String(d.getDate()).padStart(2,'0')} ${MONTHS_ID[d.getMonth()]} ${d.getFullYear()}`;
    }

    const tbody = document.getElementById('detail-table-body');
    if (!paged.length) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;color:#9baac4;">${
            q ? `Tidak ada hasil untuk "<strong>${q}</strong>".` : 'Tidak ada data reservasi.'
        }</td></tr>`;
    } else {
        tbody.innerHTML = paged.map(r => {
            const badge = STATUS_BADGE[r.status] || STATUS_BADGE.pending;
            return `
            <tr class="detail-row" data-event="${r.event_name.toLowerCase()}" data-pic="${r.pic_name.toLowerCase()}">
                <td>
                    <div style="font-weight:600;color:#111827;">${r.event_name}</div>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="avatar" style="width:28px;height:28px;font-size:0.7rem;">${r.pic_name.charAt(0)}</div>
                        <span style="font-size:0.875rem;">${r.pic_name}</span>
                    </div>
                </td>
                <td style="font-weight:500;font-size:0.875rem;">${r.room || '—'}</td>
                <td>
                    <div style="font-size:0.875rem;font-weight:500;">${fmtDate(r.date)}</div>
                    <div style="font-size:0.73rem;color:#9baac4;">${(r.start_time||'').slice(0,5)}–${(r.end_time||'').slice(0,5)}</div>
                </td>
                <td>
                    <span class="${badge.cls}" style="padding:3px 10px;border-radius:6px;font-size:0.73rem;font-weight:600;">
                        ${badge.label}
                    </span>
                </td>
                <td style="text-align:right;font-weight:600;color:#374151;">${r.attendees ?? 0}</td>
            </tr>`;
        }).join('');
    }

    // Render pagination
    const paginationEl = document.getElementById('detail-pagination');
    if (total <= DETAIL_PAGE_SIZE) {
        paginationEl.innerHTML = '';
        return;
    }

    const from = total > 0 ? start + 1 : 0;
    const to   = Math.min(start + DETAIL_PAGE_SIZE, total);

    let pageButtons = '';
    for (let p = 1; p <= totalPages; p++) {
        const active = p === detailCurrentPage;
        pageButtons += `<button onclick="goDetailPage(${p})" style="padding:6px 12px;border:1px solid ${active ? '#0A1628' : '#e5e7eb'};border-radius:7px;background:${active ? '#0A1628' : '#fff'};color:${active ? '#fff' : '#374151'};font-size:0.8rem;cursor:pointer;">${p}</button>`;
    }

    paginationEl.innerHTML = `
        <span style="font-size:0.8rem;color:#9baac4;">Showing ${from}–${to} of ${total}</span>
        <div style="display:flex;gap:6px;align-items:center;">
            <button onclick="goDetailPage(${detailCurrentPage - 1})" ${detailCurrentPage === 1 ? 'disabled' : ''} style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:${detailCurrentPage===1?'#f9fafb':'#fff'};color:${detailCurrentPage===1?'#d1d5db':'#374151'};font-size:0.8rem;cursor:${detailCurrentPage===1?'not-allowed':'pointer'};">Prev</button>
            ${pageButtons}
            <button onclick="goDetailPage(${detailCurrentPage + 1})" ${detailCurrentPage === totalPages ? 'disabled' : ''} style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:7px;background:${detailCurrentPage===totalPages?'#f9fafb':'#fff'};color:${detailCurrentPage===totalPages?'#d1d5db':'#374151'};font-size:0.8rem;cursor:${detailCurrentPage===totalPages?'not-allowed':'pointer'};">Next</button>
        </div>`;
}

function goDetailPage(p) {
    const q = (document.getElementById('table-search')?.value || '').toLowerCase().trim();
    const filtered = q
        ? detailFilteredRows.filter(r => r.event_name.toLowerCase().includes(q) || r.pic_name.toLowerCase().includes(q))
        : detailFilteredRows;
    const totalPages = Math.max(1, Math.ceil(filtered.length / DETAIL_PAGE_SIZE));
    detailCurrentPage = Math.max(1, Math.min(p, totalPages));
    renderDetailTable();
}

function exportCSV() {
    const rangeIdx = parseInt(document.getElementById('range-select').value||0, 10);
    const data = getDataForPeriod(currentPeriod, rangeIdx);
    const range = (() => {
        const now = new Date();
        function isoDate(d) {
            return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        }
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

    alert("Berhasil diexport")
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