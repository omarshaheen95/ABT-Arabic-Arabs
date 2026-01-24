<?php

namespace App\Observers;

use App\Models\UserAchievementLevel;
use App\Services\UserDataCacheService;

class UserAchievementLevelObserver
{
    protected $cacheService;

    public function __construct(UserDataCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the UserAchievementLevel "created" event.
     */
    public function created(UserAchievementLevel $userAchievementLevel): void
    {
        $this->invalidateCache($userAchievementLevel);
    }

    /**
     * Handle the UserAchievementLevel "updated" event.
     */
    public function updated(UserAchievementLevel $userAchievementLevel): void
    {
        $this->invalidateCache($userAchievementLevel);
    }

    /**
     * Handle the UserAchievementLevel "deleted" event.
     */
    public function deleted(UserAchievementLevel $userAchievementLevel): void
    {
        $this->invalidateCache($userAchievementLevel);
    }

    /**
     * Invalidate user cache
     */
    private function invalidateCache(UserAchievementLevel $userAchievementLevel): void
    {
        $this->cacheService->clearUserCache($userAchievementLevel->user_id);
    }
}
