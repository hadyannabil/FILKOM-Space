<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
