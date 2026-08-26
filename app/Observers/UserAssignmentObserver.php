<?php

namespace App\Observers;

use App\Models\UserAssignment;
use App\Services\UserDataCacheService;

class UserAssignmentObserver
{
    protected $cacheService;

    public function __construct(UserDataCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the UserAssignment "created" event.
     */
    public function created(UserAssignment $userAssignment): void
    {
        $this->invalidateCache($userAssignment);
    }

    /**
     * Handle the UserAssignment "updated" event.
     */
    public function updated(UserAssignment $userAssignment): void
    {
        // Only clear cache if completion status changed
        if ($userAssignment->isDirty('completed')) {
            $this->invalidateCache($userAssignment);
        }
    }

    /**
     * Handle the UserAssignment "deleted" event.
     */
    public function deleted(UserAssignment $userAssignment): void
    {
        $this->invalidateCache($userAssignment);
    }

    /**
     * Invalidate user cache
     */
    private function invalidateCache(UserAssignment $userAssignment): void
    {
        $this->cacheService->clearUserCache($userAssignment->user_id);
    }
}
