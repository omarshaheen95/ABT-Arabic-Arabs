<?php namespace App\Services;

use App\Helpers\Constant;
use App\Models\AchievementLevel;
use App\Models\UserAchievementLevel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AchievementLevelService
{
    private $points_list;

    public function __construct()
    {
        //for story and lesson
        $this->points_list = Constant::POINTS_LIST;
    }

    public function addPoints($type, $user_id = null): bool
    {
        return $this->handlingPoints($user_id, $type);
    }

    public function addCustomPoints($points, $user_id = null): bool
    {
        return $this->handlingPoints($user_id, null, $points);
    }

    public function getPoints($key): int
    {
        return $this->points_list[$key] ?? 0;
    }

    public function getAchievementLevels($user_id): Collection
    {
        return UserAchievementLevel::with('achievementLevel')->where('user_id', $user_id)->get();
    }

    private function handlingPoints($user_id = null, $type = null, $points = null): bool
    {
        if (!$user_id) {
            $user_id = \Auth::guard('web')->user()->id;
        }

        if ($type && !$points) {
            $points = $this->getPoints($type);
        }

        $user_achievement_level = UserAchievementLevel::with('achievementLevel')
            ->where('user_id', $user_id)
            ->where('achieved', false)
            ->first();

        // If no active level found, create the first level
        if (!$user_achievement_level) {
            $first_level = AchievementLevel::orderBy('required_points')->first();
            if (!$first_level) {
                return false;
            }
            //create the first level for user
            $user_achievement_level = UserAchievementLevel::create([
                'user_id' => $user_id,
                'achievement_level_id' => $first_level->id,
                'points' => 0,
                'achieved' => false
            ]);
        }

        // Add points and handle level progression recursively
        $this->addPointsRecursive($user_achievement_level, $points);

        return true;
    }

    private function addPointsRecursive(UserAchievementLevel $user_achievement_level, $points_to_add): void
    {
        $achievement_level = $user_achievement_level->achievementLevel;
        $new_points = $user_achievement_level->points + $points_to_add;

        // Check if user levels up
        if ($new_points >= $achievement_level->required_points) {
            $remaining_points = $new_points - $achievement_level->required_points;

            // Mark current level as achieved
            $user_achievement_level->update([
                'points' => $achievement_level->required_points,
                'achieved' => true,
                'achieved_at' => Carbon::now(),
            ]);

            // Get next level by required_points, not by ID
            $next_level = AchievementLevel::where('required_points', '>', $achievement_level->required_points)
                ->orderBy('required_points', 'asc')
                ->first();

            if ($next_level) {
                // Create next level record
                $next_user_level = UserAchievementLevel::create([
                    'user_id' => $user_achievement_level->user_id,
                    'achievement_level_id' => $next_level->id,
                    'points' => 0,
                    'achieved' => false
                ]);

                // Recursively add remaining points to check for multiple level-ups
                if ($remaining_points > 0) {
                    $this->addPointsRecursive($next_user_level, $remaining_points);
                }
            }
        } else {
            // Just update points, no level up
            $user_achievement_level->update([
                'points' => $new_points,
            ]);
        }
    }


}
