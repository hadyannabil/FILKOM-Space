<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

define('RESERVATIONS_FILE', storage_path('app/reservations.json'));

function readReservations(): array
{
    if (!file_exists(RESERVATIONS_FILE)) {
        return [];
    }
    $json = file_get_contents(RESERVATIONS_FILE);
    return json_decode($json, true) ?? [];
}

function writeReservations(array $data): void
{
    file_put_contents(RESERVATIONS_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password yang dimasukkan salah.',
    ]);
})->name('login.proses');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/', function (Request $request) {
    $selectedDate = $request->query('date', now()->toDateString());
    $startTime = $request->query('start_time', '08:00');
    $endTime = $request->query('end_time', '10:00');
    $selectedTime = $startTime . ' - ' . $endTime;

    $allRooms = [
        ['title' => 'Room F3.1', 'capacity' => '40', 'image' => 'ruang-kelas.webp', 'slug' => 'Room F3.1'],
        ['title' => 'Algorithm Auditorium', 'capacity' => '150', 'image' => 'auditorium-algoritma.webp', 'slug' => 'Algorithm Auditorium'],
        ['title' => 'Room G1.3', 'capacity' => '30', 'image' => 'lab.webp', 'slug' => 'Room G1.3'],
    ];

    $reservations = readReservations();

    $availableRooms = array_filter($allRooms, function($room) use ($reservations, $selectedDate, $selectedTime) {
        foreach ($reservations as $res) {
            if ($res['room_name'] === $room['title'] && 
                $res['date'] === $selectedDate && 
                $res['time'] === $selectedTime && 
                !in_array($res['status'], ['Rejected', 'Cancelled', 'Completed'])) { 
                return false; 
            }
        }
        return true;
    });

    return view('dashboard', [
        'availableRooms' => $availableRooms,
        'selectedDate'   => $selectedDate,
        'startTime'      => $startTime,
        'endTime'        => $endTime,
        'selectedTime'   => $selectedTime
    ]);
})->name('dashboard');

Route::get('/reserve/{room}', function (Request $request, $room) {
    $date = $request->query('date', 'Belum Dipilih'); 
    $time = $request->query('time', 'Belum Dipilih');

    return view('reservation', [
        'roomName'     => $room,
        'selectedDate' => $date,
        'selectedTime' => $time
    ]);
})->middleware('auth')->name('reserve');

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

    $reservations = readReservations();

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

    writeReservations($reservations);

    return redirect()->route('history')->with('success', 'Reservasi berhasil diajukan!');
})->middleware('auth')->name('reserve.submit');

Route::get('/history', function () {
    $reservations = readReservations();

    usort($reservations, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

    $total    = count($reservations);
    $pending  = count(array_filter($reservations, fn($r) => $r['status'] === 'Pending'));
    $approved = count(array_filter($reservations, fn($r) => $r['status'] === 'Approved'));
    $rejected = count(array_filter($reservations, fn($r) => $r['status'] === 'Rejected'));

    return view('history', compact('reservations', 'total', 'pending', 'approved', 'rejected'));
})->name('history');

Route::patch('/reserve/{id}/cancel', function ($id) {
    $reservations = readReservations();
    $found = false;

    foreach ($reservations as &$reservation) {
        if ($reservation['id'] === $id) {
            if (in_array($reservation['status'], ['Pending', 'Approved'])) {
                $reservation['status'] = 'Cancelled';
                $found = true;
                break;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status reservasi ini tidak dapat dibatalkan.'
                ], 400);
            }
        }
    }

    if ($found) {
        writeReservations($reservations);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);

})->middleware('auth')->name('reserve.cancel');