<?php

namespace App\Observers;

use App\Models\UserStoryAssignment;
use App\Services\UserDataCacheService;

class UserStoryAssignmentObserver
{
    protected $cacheService;

    public function __construct(UserDataCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the UserStoryAssignment "created" event.
     */
    public function created(UserStoryAssignment $userStoryAssignment): void
    {
        $this->invalidateCache($userStoryAssignment);
    }

    /**
     * Handle the UserStoryAssignment "updated" event.
     */
    public function updated(UserStoryAssignment $userStoryAssignment): void
    {
        // Only clear cache if completion status changed
        if ($userStoryAssignment->isDirty('completed')) {
            $this->invalidateCache($userStoryAssignment);
        }
    }

    /**
     * Handle the UserStoryAssignment "deleted" event.
     */
    public function deleted(UserStoryAssignment $userStoryAssignment): void
    {
        $this->invalidateCache($userStoryAssignment);
    }

    /**
     * Invalidate user cache
     */
    private function invalidateCache(UserStoryAssignment $userStoryAssignment): void
    {
        $this->cacheService->clearUserCache($userStoryAssignment->user_id);
    }
}
