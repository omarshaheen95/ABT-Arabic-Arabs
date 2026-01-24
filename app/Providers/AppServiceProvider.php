<?php

namespace App\Providers;

use App\Models\UserAchievementLevel;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use App\Observers\UserAchievementLevelObserver;
use App\Observers\UserAssignmentObserver;
use App\Observers\UserStoryAssignmentObserver;
use App\Observers\UserTrackerObserver;
use App\Observers\UserTrackerStoryObserver;
use Illuminate\Pagination\Paginator;
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

    }
}
