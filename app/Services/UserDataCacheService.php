<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class UserDataCacheService
{
    const CACHE_PREFIX = 'user_data_';
//    const CACHE_TTL = 86400; // 24 hours
    const CACHE_TTL = 3600; // 1 hours

    /**
     * Get cached user data or generate new if not exists
     */
    public function getUserData(?int $userId = null): array
    {
        $userId = $userId ?? Auth::guard('web')->id();

        if (!$userId) {
            return [];
        }

        $cacheKey = $this->getCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            return $this->generateUserData($userId);
        });
    }

    /**
     * Get cached dashboard data or generate new if not exists
     */
    public function getDashboardData(?int $userId = null): array
    {
        $userId = $userId ?? Auth::guard('web')->id();

        if (!$userId) {
            return [];
        }

        $cacheKey = $this->getDashboardCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            $data = new UserDashboard();
            return $data->getData();
        });
    }

    /**
     * Generate fresh user data
     */
    private function generateUserData(int $userId): array
    {
        $user = User::with(['year', 'package'])->find($userId);

        if (!$user) {
            return [];
        }

        app()->setLocale('en');
        $dashboard_data = $this->getDashboardData($userId);
        $achievement_level = $dashboard_data['user_achievement_level'];

        return [
            'name' => $user->name,
            'fullName' => $user->name,
            'avatar' => $user->image,
            'gradeArabic' => $user->grade->name,
            'package_name' => $user->package->name,
            'package' => $user->package->name,
            'yearArabic' => optional($user->year)->name,
            'levelName' => $achievement_level ? optional(optional($achievement_level)->achievementLevel)->getOriginal('name') : 'No Level',
            'levelNameArabic' => $achievement_level ? optional(optional($achievement_level)->achievementLevel)->getOriginal('name') : 'بدون مستوى',
            'currentXp' => $achievement_level->points ?? 0,
            'levelIcon' => $achievement_level
                ? asset($achievement_level->achievementLevel->badge_icon)
                : asset('user_assets/images/illustrations/level-badge.svg'),
            'maxXp' => optional(optional($achievement_level)->achievementLevel)->required_points ?? 0,
            'streak' => $dashboard_data['streak']['current'] ?? 0,
            'uncompletedLessons' => $dashboard_data['assignments_counts']['lessons_count'],
            'uncompletedStories' => $dashboard_data['assignments_counts']['stories_count'],
        ];
    }

    /**
     * Clear user data cache
     */
    public function clearUserCache(int $userId): void
    {
        Cache::forget($this->getCacheKey($userId));
        Cache::forget($this->getDashboardCacheKey($userId));
    }


    /**
     * Get cache key for user data
     */
    private function getCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . $userId;
    }

    /**
     * Get cache key for dashboard data
     */
    private function getDashboardCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . 'dashboard_' . $userId;
    }

    /**
     * Clear all user caches (useful for maintenance)
     */
    public function clearAllUserCaches(): void
    {
        Cache::flush();
    }
}
