<?php

use App\Http\Controllers\Admin\AdminController;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('login'))->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    })->name('login.proses');

    Route::get('/register', fn () => view('register'))->name('register');

    Route::post('/register', function (Request $request) {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',  // always register as regular user
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang.');
    })->name('register.proses');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/approvals',              [AdminController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{reservation}',[AdminController::class, 'approvalDetail'])->name('approval.detail');
    Route::post('/approvals/{reservation}/approve', [AdminController::class, 'approve'])->name('approval.approve');
    Route::post('/approvals/{reservation}/reject',  [AdminController::class, 'reject'])->name('approval.reject');
    Route::get('/reports',                [AdminController::class, 'reports'])->name('reports');
});

Route::get('/', function (Request $request) {
    $selectedDate = $request->query('date', now()->toDateString());
    $startTime    = $request->query('start_time', '08:00');
    $endTime      = $request->query('end_time', '10:00');
    $selectedTime = $startTime . ' - ' . $endTime;

    try {
        $allRooms = Room::active()->get()->map(fn ($r) => [
            'title'    => $r->name,
            'capacity' => (string) $r->capacity,
            'image'    => $r->image ?? 'ruang-kelas.webp',
            'slug'     => $r->name,
        ])->toArray();
    } catch (\Exception $e) {
        $allRooms = [
            ['title' => 'Room F3.1',            'capacity' => '40',  'image' => 'ruang-kelas.webp',          'slug' => 'Room F3.1'],
            ['title' => 'Algorithm Auditorium', 'capacity' => '150', 'image' => 'auditorium-algoritma.webp', 'slug' => 'Algorithm Auditorium'],
            ['title' => 'Room G1.3',            'capacity' => '30',  'image' => 'lab.webp',                  'slug' => 'Room G1.3'],
        ];
    }

    try {
        $bookedRooms = Reservation::where('reservation_date', $selectedDate)
            ->whereRaw("CONCAT(start_time, ' - ', end_time) = ?", [$selectedTime])
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->pluck('room_id')
            ->toArray();

        $availableRooms = array_filter($allRooms, function ($room) use ($bookedRooms) {
            $r = Room::where('name', $room['title'])->first();
            return $r ? !in_array($r->id, $bookedRooms) : true;
        });
    } catch (\Exception $e) {
        $availableRooms = $allRooms;
    }

    return view('dashboard', [
        'availableRooms' => $availableRooms,
        'selectedDate'   => $selectedDate,
        'startTime'      => $startTime,
        'endTime'        => $endTime,
        'selectedTime'   => $selectedTime,
    ]);
})->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/reserve/{room}', function (Request $request, $room) {
        return view('reservation', [
            'roomName'     => $room,
            'selectedDate' => $request->query('date', 'Belum Dipilih'),
            'selectedTime' => $request->query('time', 'Belum Dipilih'),
        ]);
    })->name('reserve');

    Route::post('/reserve/submit', function (Request $request) {
        $request->validate([
            'event_name'      => ['required', 'string', 'max:255'],
            'pic_name'        => ['required', 'string', 'max:255'],
            'attendees'       => ['required', 'string', 'max:100'],
            'room'            => ['required', 'string'],
            'selected_date'   => ['required', 'string'],
            'selected_time'   => ['required', 'string'],
            'approval_letter' => ['required', 'mimes:pdf', 'max:10240'],
        ]);

        $filePath = '';
        if ($request->hasFile('approval_letter')) {
            $filePath = $request->file('approval_letter')->store('letters', 'public');
        }

        $timeParts = explode(' - ', $request->selected_time);
        $startTime = $timeParts[0] ?? '08:00';
        $endTime   = $timeParts[1] ?? '10:00';

        try {
            $room = Room::where('name', $request->room)->first();

            Reservation::create([
                'request_id'      => Reservation::generateRequestId(),
                'user_id'         => Auth::id(),
                'room_id'         => $room?->id ?? 1,
                'event_name'      => $request->event_name,
                'event_type'      => $request->event_type ?? 'Academic Event',
                'pic_name'        => $request->pic_name,
                'pic_email'       => Auth::user()->email,
                'attendees'       => (int) $request->attendees,
                'notes'           => $request->notes ?? '',
                'reservation_date'=> $request->selected_date,
                'start_time'      => $startTime,
                'end_time'        => $endTime,
                'approval_letter' => $filePath,
                'status'          => 'pending',
            ]);
        } catch (\Exception $e) {
            $reservations   = _readJsonReservations();
            $reservations[] = [
                'id'              => uniqid('rsv_', true),
                'event_name'      => $request->event_name,
                'pic_name'        => $request->pic_name,
                'attendees'       => $request->attendees,
                'notes'           => $request->notes ?? '',
                'room_name'       => $request->room,
                'date'            => $request->selected_date,
                'time'            => $request->selected_time,
                'approval_letter' => $filePath,
                'status'          => 'Pending',
                'created_at'      => now()->toDateTimeString(),
            ];
            _writeJsonReservations($reservations);
        }

        return redirect()->route('history')->with('success', 'Reservasi berhasil diajukan!');
    })->name('reserve.submit');

    Route::get('/history', function () {
        try {
            $reservations = Reservation::with('room')
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->get();

            $total    = $reservations->count();
            $pending  = $reservations->where('status', 'pending')->count();
            $approved = $reservations->where('status', 'approved')->count();
            $rejected = $reservations->where('status', 'rejected')->count();

            return view('history', compact('reservations', 'total', 'pending', 'approved', 'rejected'));
        } catch (\Exception $e) {
            $reservations = _readJsonReservations();
            usort($reservations, fn ($a, $b) => strcmp($b['created_at'], $a['created_at']));
            $total    = count($reservations);
            $pending  = count(array_filter($reservations, fn ($r) => $r['status'] === 'Pending'));
            $approved = count(array_filter($reservations, fn ($r) => $r['status'] === 'Approved'));
            $rejected = count(array_filter($reservations, fn ($r) => $r['status'] === 'Rejected'));
            return view('history', compact('reservations', 'total', 'pending', 'approved', 'rejected'));
        }
    })->name('history');

    Route::patch('/reserve/{id}/cancel', function ($id) {
        try {
            $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            if (!in_array($reservation->status, ['pending', 'approved'])) {
                return response()->json(['success' => false, 'message' => 'Status reservasi ini tidak dapat dibatalkan.'], 400);
            }
            $reservation->update(['status' => 'cancelled']);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // JSON fallback
            $reservations = _readJsonReservations();
            $found = false;
            foreach ($reservations as &$r) {
                if ($r['id'] === $id) {
                    if (in_array($r['status'], ['Pending', 'Approved'])) {
                        $r['status'] = 'Cancelled';
                        $found = true;
                    } else {
                        return response()->json(['success' => false, 'message' => 'Tidak dapat dibatalkan.'], 400);
                    }
                    break;
                }
            }
            if ($found) { _writeJsonReservations($reservations); return response()->json(['success' => true]); }
            return response()->json(['success' => false]);
        }
    })->name('reserve.cancel');
});

function _readJsonReservations(): array {
    $file = storage_path('app/reservations.json');
    return file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
}

function _writeJsonReservations(array $data): void {
    file_put_contents(storage_path('app/reservations.json'), json_encode($data, JSON_PRETTY_PRINT));
}
