<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pendingCount  = Reservation::where('status', 'pending')->count();
        $todayEvents   = Reservation::where('reservation_date', today())
                            ->whereIn('status', ['approved', 'pending'])
                            ->count();
        $totalRooms    = Room::count();
        $availableNow  = Room::active()->count();   

        $requests = Reservation::with(['user', 'room'])
                        ->where('status', 'pending')
                        ->orderByDesc('created_at')
                        ->paginate(7);

        return view('admin.dashboard', compact(
            'pendingCount', 'todayEvents', 'totalRooms', 'availableNow', 'requests'
        ));
    }

    public function approvals(Request $request)
    {
        $query = Reservation::with(['user', 'room'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->paginate(10);

        return view('admin.approvals', compact('reservations'));
    }

    public function approvalDetail(Reservation $reservation)
    {
        $reservation->load(['user', 'room', 'reviewer']);
        return view('admin.approval-detail', compact('reservation'));
    }

    public function approve(Reservation $reservation)
    {
        $reservation->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.approvals')
               ->with('success', 'Reservasi berhasil disetujui.');
    }

    public function reject(Request $request, Reservation $reservation)
    {
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reservation->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        return redirect()->route('admin.approvals')
               ->with('success', 'Reservasi telah ditolak.');
    }

    public function reports(Request $request)
    {
        $reservations = Reservation::with(['room', 'user'])
            ->orderByDesc('reservation_date')
            ->paginate(10);

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

        return view('admin.reports', compact('reservations', 'chartData'));
    }
}
