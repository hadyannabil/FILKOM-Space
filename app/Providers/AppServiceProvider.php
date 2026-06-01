<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $count = Notification::where('user_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
                
                $notifs = Notification::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

                $view->with('unreadCount', $count)
                    ->with('notifications', $notifs);
            } else {
                $view->with('unreadCount', 0)->with('notifications', collect());
            }
        });
    }
}