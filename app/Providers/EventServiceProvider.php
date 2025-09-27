<?php

namespace App\Providers;

use App\Models\Charge;
use App\Models\Professor;
use App\Models\Session;
use App\Models\SessionExtra;
use App\Models\SessionStudent;
use App\Models\Student;
use App\Observers\ChargeObserver;
use App\Observers\ProfessorObserver;
use App\Observers\SessionExtraObserver;
use App\Observers\SessionObserver;
use App\Observers\SessionStudentObserver;
use App\Observers\StudentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Session::observe(SessionObserver::class);
        SessionExtra::observe(SessionExtraObserver::class);
        SessionStudent::observe(SessionStudentObserver::class);
        Charge::observe(ChargeObserver::class);
        Professor::observe(ProfessorObserver::class);
        Student::observe(StudentObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
