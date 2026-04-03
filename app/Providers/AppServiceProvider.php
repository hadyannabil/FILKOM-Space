<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // 1. Ambil jumlah yang belum dibaca
                $count = Notification::where('user_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
                
                // 2. Ambil 5 data notifikasi terbaru milik user tersebut
                $notifs = Notification::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

                // 3. Kirim keduanya ke semua halaman Blade
                $view->with('unreadCount', $count)
                    ->with('notifications', $notifs);
            } else {
                $view->with('unreadCount', 0)->with('notifications', collect());
            }
        });
    }
}
