<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pendingCount   = Reservation::where('status', 'pending')->count();
        $todayEvents    = Reservation::where('reservation_date', today())
                            ->where('status', 'approved')
                            ->count();
        $busyRoomIds = Reservation::whereIn('status', ['approved', 'pending'])
                            ->where('reservation_date', today())
                            ->whereRaw('start_time <= ?', [now()->format('H:i:s')])
                            ->whereRaw('end_time >= ?', [now()->format('H:i:s')])
                            ->pluck('room_id');
        $availableRooms = Room::active()->whereNotIn('id', $busyRoomIds)->count();

        $requests = Reservation::with(['user', 'room'])
                        ->where('status', 'pending')
                        ->where('reservation_date', '>=', today())
                        ->orderBy('reservation_date')
                        ->orderBy('start_time')
                        ->paginate(7);

        return view('admin.dashboard', compact(
            'pendingCount', 'todayEvents', 'availableRooms', 'requests'
        ));
    }

    public function approvals(Request $request)
    {
        $query = Reservation::with(['user', 'room'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('event_name',  'like', "%{$q}%")
                    ->orWhere('pic_name',  'like', "%{$q}%")
                    ->orWhere('request_id','like', "%{$q}%");
            });
        }

        $reservations = $query->paginate(10)->appends($request->query());

        return view('admin.approvals', compact('reservations'));
    }

    public function searchApprovals(Request $request)
    {
        $q      = $request->query('q', '');
        $status = $request->query('status', '');
        $page   = max(1, (int) $request->query('page', 1));

        $query = Reservation::with(['room'])
            ->orderByDesc('created_at');

        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('event_name',  'like', "%{$q}%")
                    ->orWhere('pic_name',  'like', "%{$q}%")
                    ->orWhere('request_id','like', "%{$q}%");
            });
        }

        $reservations = $query->paginate(10, ['*'], 'page', $page);

        $statusMap = [
            'pending'   => ['label' => 'Pending',   'class' => 'badge-pending'],
            'approved'  => ['label' => 'Approved',  'class' => 'badge-approved'],
            'rejected'  => ['label' => 'Rejected',  'class' => 'badge-rejected'],
            'cancelled' => ['label' => 'Cancelled', 'class' => 'badge-cancelled'],
        ];

        $data = $reservations->map(function ($r) use ($statusMap) {
            $s = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'class' => 'badge-cancelled'];
            return [
                'id'         => $r->id,
                'request_id' => $r->request_id,
                'event_name' => $r->event_name,
                'pic_name'   => $r->pic_name,
                'room'       => $r->room->name ?? '—',
                'date'       => \Carbon\Carbon::parse($r->reservation_date)->format('M j, Y'),
                'status'     => $r->status,
                'badge_label'=> $s['label'],
                'badge_class'=> $s['class'],
                'detail_url' => route('admin.approval.detail', $r->id),
            ];
        });

        return response()->json([
            'data'          => $data,
            'total'         => $reservations->total(),
            'current_page'  => $reservations->currentPage(),
            'last_page'     => $reservations->lastPage(),
            'from'          => $reservations->firstItem(),
            'to'            => $reservations->lastItem(),
        ]);
    }

    public function approvalDetail(Reservation $reservation)
    {
        $reservation->load(['user', 'room', 'reviewer']);
        return view('admin.approval-detail', compact('reservation'));
    }

    public function viewLetter($id)
    {
        $reservation = Reservation::findOrFail($id);
        $path = storage_path('app/public/' . $reservation->approval_letter);

        abort_unless(file_exists($path), 404);

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }

    public function approve(Reservation $reservation)
    {
        $reservation->load('room');

        $reservation->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $reservation->user_id,
            'title'   => 'Reservasi Disetujui',
            'message' => 'Reservasi ruangan ' . ($reservation->room->name ?? '-') . ' untuk acara "' . $reservation->event_name . '" telah disetujui.',
            'is_read' => false,
        ]);

        return redirect()->route('admin.approvals')
               ->with('success', 'Reservasi berhasil disetujui.');
    }

    public function reject(Request $request, Reservation $reservation)
    {
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reservation->load('room');

        $reservation->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        Notification::create([
            'user_id' => $reservation->user_id,
            'title'   => 'Reservasi Ditolak',
            'message' => 'Reservasi ruangan ' . ($reservation->room->name ?? '-') . ' untuk acara "' . $reservation->event_name . '" ditolak.'
                       . ($request->rejection_reason ? ' Alasan: ' . $request->rejection_reason : ''),
            'is_read' => false,
        ]);

        return redirect()->route('admin.approvals')
               ->with('success', 'Reservasi telah ditolak.');
    }

    public function reports(Request $request)
    {
        $chartData = Reservation::with('room')
            ->orderBy('reservation_date')
            ->get()
            ->map(fn ($r) => [
                'id'          => $r->id,
                'event_name'  => $r->event_name,
                'event_type'  => $r->event_type ?? 'Lainnya',
                'pic_name'    => $r->pic_name,
                'room'        => $r->room->name ?? 'Unknown',
                'date'        => $r->reservation_date instanceof \Carbon\Carbon
                                    ? $r->reservation_date->toDateString()
                                    : (string) $r->reservation_date,
                'start_time'  => substr($r->start_time, 0, 5),
                'end_time'    => substr($r->end_time, 0, 5),
                'attendees'   => (int) $r->attendees,
                'status'      => $r->status,
            ])
            ->values()
            ->toArray();

        return view('admin.reports', compact('chartData'));
    }
}