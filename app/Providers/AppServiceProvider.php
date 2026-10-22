<?php

namespace App\Providers;

use App\Listeners\UserEventSubscriber;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAchievementLevel;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use App\Observers\SchoolObserver;
use App\Observers\SupervisorObserver;
use App\Observers\TeacherObserver;
use App\Observers\UserAchievementLevelObserver;
use App\Observers\UserAssignmentObserver;
use App\Observers\UserObserver;
use App\Observers\UserStoryAssignmentObserver;
use App\Observers\UserTrackerObserver;
use App\Observers\UserTrackerStoryObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'production') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        UserTracker::observe(UserTrackerObserver::class);
        UserTrackerStory::observe(UserTrackerStoryObserver::class);
        UserAchievementLevel::observe(UserAchievementLevelObserver::class);
        UserAssignment::observe(UserAssignmentObserver::class);
        UserStoryAssignment::observe(UserStoryAssignmentObserver::class);

        // What EventServiceProvider registered in Laravel 8
        Event::subscribe(UserEventSubscriber::class);
        User::observe(UserObserver::class);
        School::observe(SchoolObserver::class);
        Teacher::observe(TeacherObserver::class);
        Supervisor::observe(SupervisorObserver::class);

        // What RouteServiceProvider configured in Laravel 8
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // The log viewer is behind the manager login (config/log-viewer.php).
        // opcodesio/log-viewer refuses everybody in production unless told
        // otherwise: whoever passed that middleware is a manager.
        \Opcodes\LogViewer\Facades\LogViewer::auth(function ($request) {
            return auth('manager')->check();
        });

        // The students' reset link mail (the framework's notification): Laravel 13
        // changed its subject to "Reset your password". The rest is unchanged.
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject(\Illuminate\Support\Facades\Lang::get('Reset Password Notification'))
                ->line(\Illuminate\Support\Facades\Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->action(\Illuminate\Support\Facades\Lang::get('Reset Password'), $url)
                ->line(\Illuminate\Support\Facades\Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                ->line(\Illuminate\Support\Facades\Lang::get('If you did not request a password reset, no further action is required.'));
        });
    }
}
