<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
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

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/reserve/{room}', function ($room) {
    return view('reservation', [
        'roomName' => $room
    ]);
})->middleware('auth')->name('reserve');

Route::get('/history', function () {
    return view('history'); 
})->name('history');

Route::post('/reserve/submit', function (\Illuminate\Http\Request $request) {
    return redirect()->route('history');
})->name('reserve.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/login');
})->name('logout');

