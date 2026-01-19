<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use App\Services\UserDashboard;
use App\Services\UserDataCacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareUserDataToViewsMiddleware
{
    protected $cacheService;

    public function __construct(UserDataCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            app()->setLocale('en');

            // Get cached user data
            $user_data = $this->cacheService->getUserData();
            $dashboard_data = $this->cacheService->getDashboardData();

            View::share('user_data', $user_data);
            View::share('dashboard_data', $dashboard_data);
        }

        return $next($request);
    }
}
//class ShareUserDataToViewsMiddleware
//{
//    public function handle(Request $request, Closure $next)
//    {
//        if (Auth::guard('web')->check()) {
//            $user = Auth::guard('web')->user();
//            app()->setLocale('ar');
//            $data = new UserDashboard();
//            $dashboard_data = $data->getData();
//
//            $user->load(['year','package']);
//
//            $achievement_level = $dashboard_data['user_achievement_level'];
//
//
//            $user_data = [
//                'name' => $user->name,
//                'fullName' => $user->name,
//                'avatar' => $user->image,
//                'gradeArabic' => $user->grade->name,
//                'package_name' => $user->package->name,
//                'package' => $user->package->name,
//                'yearArabic' => optional($user->year)->name,
//                'levelName' => $achievement_level ? optional(optional($achievement_level)->achievementLevel)->name: 'No Level',
//                'currentXp' => $achievement_level->points ?? 0,
//
//                'levelIcon' => $achievement_level ?
//                    asset($achievement_level->achievementLevel->badge_icon):
//                    asset('user_assets/images/illustrations/level-badge.svg'),
//
//                'maxXp' => optional(optional($achievement_level)->achievementLevel)->required_points ?? 0,
//                'streak' => $dashboard_data['streak']['current'] ?? 0,
//                'uncompletedLessons' => $dashboard_data['assignments_counts']['lessons_count'],
//                'uncompletedStories' => $dashboard_data['assignments_counts']['stories_count'],
//            ];
//
//            View::share('user_data', $user_data);
//            View::share('dashboard_data', $dashboard_data);
//        }
//
//        return $next($request);
//    }
//}
