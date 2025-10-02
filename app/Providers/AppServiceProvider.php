<?php

namespace App\Providers;

use App\Models\UserTracker;
use App\Models\UserTrackerStory;
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
//        UserTracker::observe(UserTrackerObserver::class);
//        UserTrackerStory::observe(UserTrackerStoryObserver::class);
    }
}
