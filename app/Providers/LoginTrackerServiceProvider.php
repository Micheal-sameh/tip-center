<?php

namespace App\Providers;

use App\Models\UserLogin;
use App\Repositories\ChargeRepository;
use App\Services\SessionService;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class LoginTrackerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Normal login
        Event::listen(Login::class, function ($event) {
            $this->trackLogin($event->user);
        });

        // Remember-me auto login
        Event::listen(Authenticated::class, function ($event) {
            $this->trackLogin($event->user);
        });
    }

    protected function trackLogin($user)
    {
        $alreadyToday = UserLogin::whereDate('created_at', today())
            ->exists();
        UserLogin::create([
            'user_id' => $user->id,
            'ip' => request()->ip(),
        ]);
        if (! $alreadyToday) {
            $sessionService = app(SessionService::class);
            $sessionService->automaticCreateSessions();

            $chargeRepository = app(ChargeRepository::class);
            $chargeRepository->reverseGap();
        }
    }
}
