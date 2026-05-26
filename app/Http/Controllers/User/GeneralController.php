<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AchievementLevel;
use App\Models\UserAchievementLevel;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function ranking()
    {
        $user = Auth::guard('web')->user();

        // Get all achievement levels ordered by id
        $achievementLevels = AchievementLevel::orderBy('id', 'asc')->get();

        // Get user's current achievement level (not yet achieved - in progress)
        $userAchievementLevel = UserAchievementLevel::where('user_id', $user->id)
            ->where('achieved', false)
            ->with('achievementLevel')
            ->first();

        // Get current achievement level details
        $currentAchievementLevel = $userAchievementLevel
            ? $userAchievementLevel->achievementLevel
            : null;

        // Get previous and next levels based on achievement_level_id
        $previousLevel = null;
        $nextLevel = null;

        if ($currentAchievementLevel) {
            // Find current level index in the ordered list
            $currentIndex = $achievementLevels->search(function ($level) use ($currentAchievementLevel) {
                return $level->id === $currentAchievementLevel->id;
            });

            // Get previous level (the one before current in the list)
            if ($currentIndex !== false && $currentIndex > 0) {
                $previousLevel = $achievementLevels[$currentIndex - 1];
            }

            // Get next level (the one after current in the list)
            if ($currentIndex !== false && $currentIndex < $achievementLevels->count() - 1) {
                $nextLevel = $achievementLevels[$currentIndex + 1];
            }
        }

        // Get previous achieved levels (for display purposes)
        $previousAchievedLevels = UserAchievementLevel::where('user_id', $user->id)
            ->where('achieved', true)
            ->with('achievementLevel')
            ->get();

        // Get users in the same achievement level with their rankings
        // Filter by: school_id, grade, year_id, current level, and section
        $usersInSameLevel = [];
        if ($currentAchievementLevel) {
            $usersInSameLevel = UserAchievementLevel::where('achievement_level_id', $currentAchievementLevel->id)
                ->where('achieved', false)
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('school_id', $user->school_id)
                        ->where('grade_id', $user->grade_id)
                        ->where('year_id', $user->year_id)
                        ->where('section', $user->section);
                })
                ->with('user')
                ->orderBy('points', 'desc')
                ->get();
        }

        return view('user.ranking.index', compact(
            'userAchievementLevel',
            'currentAchievementLevel',
            'achievementLevels',
            'previousLevel',
            'previousAchievedLevels',
            'nextLevel',
            'usersInSameLevel'
        ));
    }
}
