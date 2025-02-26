<?php

namespace App\Providers;

use App\Listeners\UserEventSubscriber;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Observers\SchoolObserver;
use App\Observers\SupervisorObserver;
use App\Observers\TeacherObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $subscribe = [
        UserEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        School::observe(SchoolObserver::class);
        Teacher::observe(TeacherObserver::class);
        Supervisor::observe(SupervisorObserver::class);

    }
}
